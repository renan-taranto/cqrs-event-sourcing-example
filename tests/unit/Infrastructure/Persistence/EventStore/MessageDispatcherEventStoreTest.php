<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\Infrastructure\Persistence\EventStore;

use Codeception\Specify;
use Codeception\Test\Unit;
use Symfony\Component\Messenger\MessageBusInterface;
use Taranto\ListMaker\Domain\Model\Common\AggregateVersion;
use Taranto\ListMaker\Domain\Model\Common\DomainEvent;
use Taranto\ListMaker\Domain\Model\Common\DomainEvents;
use Taranto\ListMaker\Infrastructure\Persistence\EventStore\EventStore;
use Taranto\ListMaker\Infrastructure\Persistence\EventStore\MessageDispatcherEventStore;

/**
 * Class MessageDispatcherEventStoreTest
 * @package Taranto\ListMaker\Tests\unit\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MessageDispatcherEventStoreTest extends Unit
{
    use Specify;

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

    protected function _before(): void
    {
        $this->eventStore = \Mockery::spy(EventStore::class);
        $this->eventBus = \Mockery::spy(MessageBusInterface::class);

        $this->event = \Mockery::mock(DomainEvent::class);
        $this->domainEvents = new DomainEvents([$this->event]);

        $this->aggregateVersion = AggregateVersion::fromVersion(1);

        $this->messageDispatcherEventStore = new MessageDispatcherEventStore(
            $this->eventStore,
            $this->eventBus
        );
    }

    /**
     * @test
     */
    public function commit(): void
    {
        $this->describe('Commit', function() {
            $this->should('dispatch events after committing them', function() {
                $this->messageDispatcherEventStore->commit($this->domainEvents, $this->aggregateVersion);

                $this->eventStore->shouldHaveReceived('commit')->with($this->domainEvents, $this->aggregateVersion);
                $this->eventBus->shouldHaveReceived('dispatch')->with($this->event);
            });
        });
    }
}
