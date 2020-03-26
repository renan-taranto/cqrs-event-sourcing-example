<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Symfony\Component\Serializer\SerializerInterface;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateHistory;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateVersion;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvents;

/**
 * Class MySqlEventStore
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MySqlEventStore implements EventStore
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * MySqlEventStore constructor.
     * @param Connection $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param DomainEvents $events
     * @param AggregateVersion $expectedVersion
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function commit(IdentifiesAggregate $aggregateId, DomainEvents $events, AggregateVersion $expectedVersion): void
    {
        try {
            $this->connection->beginTransaction();

            $this->checkForConcurrency($aggregateId, $expectedVersion);
            $this->insertDomainEvents($events, $expectedVersion);

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param AggregateVersion $expectedVersion
     * @throws \Doctrine\DBAL\DBALException
     */
    private function checkForConcurrency(IdentifiesAggregate $aggregateId, AggregateVersion $expectedVersion): void
    {
        $stmt = $this->connection->executeQuery(
            'SELECT aggregate_version 
                        FROM event_stream 
                        WHERE aggregate_id = :aggregate_id 
                        ORDER BY aggregate_version DESC
                        LIMIT 1',
            [':aggregate_id' => (string) $aggregateId]
        );
        if ((int) $stmt->fetchColumn() !== $expectedVersion->version()) {
            throw new ConcurrencyException();
        }
    }

    /**
     * @param DomainEvents $events
     * @param AggregateVersion $currentVersion
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insertDomainEvents(DomainEvents $events, AggregateVersion $currentVersion): void
    {
        $stmt = $this->connection->prepare("
                INSERT INTO event_stream (aggregate_id, aggregate_version, event_type, payload, created_at)
                VALUES(:aggregate_id, :aggregate_version, :event_type, :payload, :created_at)
            ");

        $version = $currentVersion->copy();
        foreach ($events as $event) {
            $version = $version->next();

            /** @var $event DomainEvent */
            $stmt->execute([
                ':aggregate_id' => (string) $event->aggregateId(),
                ':aggregate_version' => $version->version(),
                ':event_type' => $event->eventType(),
                ':payload' => json_encode($event->payload()),
                ':created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateHistory
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Taranto\ListMaker\Shared\Domain\Aggregate\CorruptAggregateHistory
     */
    public function aggregateHistoryFor(IdentifiesAggregate $aggregateId): AggregateHistory
    {
        return new AggregateHistory($aggregateId, $this->loadEvents($aggregateId));
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    private function loadEvents(IdentifiesAggregate $aggregateId): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM event_stream WHERE aggregate_id = :aggregate_id");
        $stmt->execute([':aggregate_id' => (string) $aggregateId]);

        $events = [];
        while ($row = $stmt->fetch(FetchMode::ASSOCIATIVE)) {
            $events[] = $this->deserializeEvent($row);
        }
        $stmt->closeCursor();

        return $events;
    }

    /**
     * @param array $eventStreamRow
     * @return DomainEvent
     */
    private function deserializeEvent(array $eventStreamRow): DomainEvent
    {
        $eventStreamRow['payload'] = json_decode($eventStreamRow['payload'], true);
        return $this->serializer->deserialize(json_encode($eventStreamRow), DomainEvent::class, 'json');
    }
}
