<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\integration\Infrastructure\Persistence\EventStore;

use Codeception\Specify;
use Codeception\Test\Unit;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleChanged;
use Taranto\ListMaker\Domain\Model\Common\AggregateHistory;
use Taranto\ListMaker\Domain\Model\Common\AggregateVersion;
use Taranto\ListMaker\Domain\Model\Common\DomainEvent;
use Taranto\ListMaker\Domain\Model\Common\DomainEvents;
use Taranto\ListMaker\Infrastructure\Persistence\EventStore\MySqlEventStore;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class MySqlEventStoreTest
 * @package Taranto\ListMaker\Tests\integration\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MySqlEventStoreTest extends Unit
{
    use Specify;

    /**
     * @var IntegrationTester
     */
    protected $tester;

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
     * @var AggregateVersion
     */
    private $aggregateVersion;

    /**
     * @var DomainEvents
     */
    private $domainEvents;

    protected function _before(): void
    {
        $this->mySqlEventStore = $this->tester->grabService('test.service_container')->get(MySqlEventStore::class);

        $this->aggregateId = BoardId::generate();
        $this->events = [
            BoardCreated::occur((string) $this->aggregateId),
            BoardTitleChanged::occur((string) $this->aggregateId, ['title' => 'Changed Title']),
            BoardClosed::occur((string) $this->aggregateId)
        ];
        $this->domainEvents = new DomainEvents($this->events);
        $this->aggregateVersion = AggregateVersion::fromVersion(count($this->events));
    }

    /**
     * @test
     */
    public function aggregateHistoryFor(): void
    {
        $this->should('return AggregateHistory from committed events', function () {
            $this->mySqlEventStore->commit($this->domainEvents, $this->aggregateVersion);

            $aggregateHistory = $this->mySqlEventStore->aggregateHistoryFor($this->aggregateId);

            expect($aggregateHistory)->equals(new AggregateHistory($this->aggregateId, $this->events));
        });
    }
}
