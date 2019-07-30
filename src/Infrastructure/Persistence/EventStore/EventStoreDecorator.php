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

use Taranto\ListMaker\Domain\Model\Common\AggregateHistory;
use Taranto\ListMaker\Domain\Model\Common\AggregateVersion;
use Taranto\ListMaker\Domain\Model\Common\DomainEvents;
use Taranto\ListMaker\Domain\Model\Common\IdentifiesAggregate;

/**
 * Class EventStoreDecorator
 * @package Taranto\ListMaker\Infrastructure\Persistence\EventStore
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
