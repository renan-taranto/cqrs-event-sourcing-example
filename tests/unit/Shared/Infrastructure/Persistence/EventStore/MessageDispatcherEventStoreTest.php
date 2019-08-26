<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Shared\Infrastructure\Persistence\EventStore;

use Codeception\Test\Unit;
use Symfony\Component\Messenger\MessageBusInterface;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateVersion;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvents;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore\EventStore;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore\MessageDispatcherEventStore;

/**
 * Class MessageDispatcherEventStoreTest
 * @package Taranto\ListMaker\Tests\Shared\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MessageDispatcherEventStoreTest extends Unit
{
    /**
     * @var DomainEvents
     */
    private $domainEvents;

    /**
     * @var DomainEvent
     */
    private $event;

    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * @var AggregateVersion
     */
    private $aggregateVersion;

    /**
     * @var MessageDispatcherEventStore
     */
    private $messageDispatcherEventStore;

    protected function _before(): void
    {
        $this->eventStore = \Mockery::spy(EventStore::class);
        $this->eventBus = \Mockery::spy(MessageBusInterface::class);

        $this->event = \Mockery::mock(DomainEvent::class);
        $this->domainEvents = new DomainEvents([$this->event]);

        $this->aggregateId = \Mockery::mock(IdentifiesAggregate::class);
        $this->aggregateVersion = AggregateVersion::fromVersion(1);

        $this->messageDispatcherEventStore = new MessageDispatcherEventStore(
            $this->eventStore,
            $this->eventBus
        );
    }

    /**
     * @test
     */
    public function it_dispatch_events_after_committing_them(): void
    {
        $this->messageDispatcherEventStore->commit(
            $this->aggregateId,
            $this->domainEvents,
            $this->aggregateVersion
        );

        $this->eventStore->shouldHaveReceived('commit')->with(
            $this->aggregateId,
            $this->domainEvents,
            $this->aggregateVersion
        );

        $this->eventBus->shouldHaveReceived('dispatch')->with($this->event);
    }
}
