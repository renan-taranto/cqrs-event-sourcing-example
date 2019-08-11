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

use Symfony\Component\Messenger\MessageBusInterface;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateVersion;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvents;

/**
 * Class MessageDispatcherEventStore
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MessageDispatcherEventStore extends EventStoreDecorator
{
    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * MessageDispatcherEventStore constructor.
     * @param EventStore $eventStore
     * @param MessageBusInterface $eventBus
     */
    public function __construct(EventStore $eventStore, MessageBusInterface $eventBus)
    {
        parent::__construct($eventStore);
        $this->eventBus = $eventBus;
    }

    /**
     * @param DomainEvents $events
     * @param AggregateVersion $aggregateVersion
     */
    public function commit(DomainEvents $events, AggregateVersion $aggregateVersion): void
    {
        parent::commit($events, $aggregateVersion);

        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
