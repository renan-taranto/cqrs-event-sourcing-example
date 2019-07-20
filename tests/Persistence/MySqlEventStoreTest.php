<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Persistence;

use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleChanged;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardCreated;
use Taranto\ListMaker\Domain\Model\Common\AggregateHistory;
use Taranto\ListMaker\Domain\Model\Common\AggregateVersion;
use Taranto\ListMaker\Domain\Model\Common\DomainEvent;
use Taranto\ListMaker\Domain\Model\Common\DomainEvents;
use Taranto\ListMaker\Infrastructure\Persistence\EventStore\MySqlEventStore;
use Taranto\ListMaker\Tests\IntegrationTestCase;

/**
 * Class MySqlEventStoreTest
 * @package Taranto\ListMaker\Tests\Persistence
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MySqlEventStoreTest extends IntegrationTestCase
{
    /**
     * @var MySqlEventStore
     */
    private $mySqlEventStore;

    /**
     * @var BoardId
     */
    private $aggregateId;

    /**
     * @var DomainEvent[]
     */
    private $events;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mySqlEventStore = self::$container->get('Taranto\ListMaker\Infrastructure\Persistence\EventStore\MySqlEventStore');
        $this->aggregateId = BoardId::generate();
        $this->events = [
            BoardCreated::occur((string) $this->aggregateId),
            BoardTitleChanged::occur((string) $this->aggregateId, ['title' => 'Changed Title']),
            BoardClosed::occur((string) $this->aggregateId)
        ];
    }

    /**
     * @test
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Taranto\ListMaker\Domain\Model\Common\CorruptAggregateHistory
     */
    public function it_returns_an_aggregate_history(): void
    {
        $aggregateVersion = AggregateVersion::fromVersion(count($this->events));
        $domainEvents = new DomainEvents($this->events);

        $this->mySqlEventStore->commit($domainEvents, $aggregateVersion);

        $this->assertEquals(
            new AggregateHistory($this->aggregateId, $this->events),
            $this->mySqlEventStore->aggregateHistoryFor($this->aggregateId)
        );
    }
}
