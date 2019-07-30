<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Infrastructure\Persistence\EventStore;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Taranto\ListMaker\Domain\Model\Common\AggregateVersion;
use Taranto\ListMaker\Domain\Model\Common\DomainEvent;
use Taranto\ListMaker\Domain\Model\Common\DomainEvents;
use Taranto\ListMaker\Infrastructure\Persistence\EventStore\EventStore;
use Taranto\ListMaker\Infrastructure\Persistence\EventStore\MessageDispatcherEventStore;

/**
 * Class MessageDispatcherEventStoreTest
 * @package Taranto\ListMaker\Tests\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MessageDispatcherEventStoreTest extends TestCase
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
     * @var AggregateVersion
     */
    private $aggregateVersion;

    /**
     * @var MessageDispatcherEventStore
     */
    private $messageDispatcherEventStore;

    protected function setUp(): void
    {
        $this->eventBus = $this->prophesize(MessageBusInterface::class);
        $this->eventStore = $this->prophesize(EventStore::class);

        $this->event = $this->prophesize(DomainEvent::class);
        $this->domainEvents = new DomainEvents([$this->event->reveal()]);

        $this->aggregateVersion = AggregateVersion::fromVersion(1);

        $this->messageDispatcherEventStore = new MessageDispatcherEventStore(
            $this->eventStore->reveal(),
            $this->eventBus->reveal()
        );
    }

    /**
     * @test
     */
    public function it_dispatches_events_after_committing_them(): void
    {
        $this->eventStore->commit($this->domainEvents, $this->aggregateVersion)->shouldBeCalled();
        $this->eventBus->dispatch($this->event)->shouldBeCalled();

        $this->messageDispatcherEventStore->commit($this->domainEvents, $this->aggregateVersion);
    }
}
