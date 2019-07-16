<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Persistence\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Symfony\Component\Serializer\SerializerInterface;
use Taranto\ListMaker\Domain\Model\Common\AggregateHistory;
use Taranto\ListMaker\Domain\Model\Common\AggregateVersion;
use Taranto\ListMaker\Domain\Model\Common\DomainEvent;
use Taranto\ListMaker\Domain\Model\Common\DomainEvents;
use Taranto\ListMaker\Domain\Model\Common\IdentifiesAggregate;

/**
 * Class MySqlEventStore
 * @package Taranto\ListMaker\Infrastructure\Persistence\EventStore
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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function createEventStream(): void
    {
        $this->connection->exec("
            CREATE TABLE event_stream (
              `id` BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `aggregate_id` CHAR(36) NOT NULL,
              `aggregate_version` INT(11) UNSIGNED NOT NULL,
              `event_type` VARCHAR(100) NOT NULL,
              `payload` JSON NOT NULL,
              `created_at` DATETIME NOT NULL,
              UNIQUE KEY `unique_aggregate_version` (`aggregate_id`,`aggregate_version`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin
        ");
    }

    /**
     * @param DomainEvents $events
     * @param AggregateVersion $aggregateVersion
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function commit(DomainEvents $events, AggregateVersion $aggregateVersion): void
    {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare("
                INSERT INTO event_stream (aggregate_id, aggregate_version, event_type, payload, created_at)
                VALUES(:aggregate_id, :aggregate_version, :event_type, :payload, :created_at)
            ");

            $aggregateVersion = $aggregateVersion->decreaseBy(count($events));
            foreach ($events as $event) {
                $aggregateVersion = $aggregateVersion->next();

                /** @var $event DomainEvent */
                $stmt->execute([
                    ':aggregate_id' => (string) $event->aggregateId(),
                    ':aggregate_version' => $aggregateVersion->version(),
                    ':event_type' => $event->eventType(),
                    ':payload' => json_encode($event->payload()),
                    ':created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')
                ]);
            }
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateHistory
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Taranto\ListMaker\Domain\Model\Common\CorruptAggregateHistory
     */
    public function aggregateHistoryFor(IdentifiesAggregate $aggregateId): AggregateHistory
    {
        $stmt = $this->connection->prepare("SELECT * FROM event_stream WHERE aggregate_id = :aggregate_id");
        $stmt->execute([':aggregate_id' => (string) $aggregateId]);

        $events = [];
        while ($row = $stmt->fetch(FetchMode::ASSOCIATIVE)) {
            $jsonEncodedEvent = $this->eventStreamRowToJson($row);
            $events[] = $this->serializer->deserialize($jsonEncodedEvent, DomainEvent::class, 'json');
        }
        $stmt->closeCursor();

        return new AggregateHistory($aggregateId, $events);
    }

    /**
     * @param array $eventStreamRow
     * @return string
     */
    private function eventStreamRowToJson(array $eventStreamRow): string
    {
        $eventStreamRow['payload'] = json_decode($eventStreamRow['payload'], true);
        return json_encode($eventStreamRow);
    }
}
