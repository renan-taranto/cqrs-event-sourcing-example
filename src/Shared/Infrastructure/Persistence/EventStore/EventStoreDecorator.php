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

use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateHistory;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateVersion;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvents;

/**
 * Class EventStoreDecorator
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class EventStoreDecorator implements EventStore
{
    /**
     * @var EventStore
     */
    protected $eventStore;

    /**
     * EventStoreDecorator constructor.
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function createEventStream(): void
    {
        $this->eventStore->createEventStream();
    }

    /**
     * @param DomainEvents $events
     * @param AggregateVersion $aggregateVersion
     */
    public function commit(DomainEvents $events, AggregateVersion $aggregateVersion): void
    {
        $this->eventStore->commit($events, $aggregateVersion);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateHistory
     */
    public function aggregateHistoryFor(IdentifiesAggregate $aggregateId): AggregateHistory
    {
        return $this->eventStore->aggregateHistoryFor($aggregateId);
    }
}
