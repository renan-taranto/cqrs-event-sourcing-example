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
 * Interface EventStore
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface EventStore
{
    public function createEventStream(): void;

    /**
     * @param DomainEvents $events
     * @param AggregateVersion $aggregateVersion
     */
    public function commit(DomainEvents $events, AggregateVersion $aggregateVersion): void;

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateHistory
     */
    public function aggregateHistoryFor(IdentifiesAggregate $aggregateId): AggregateHistory;
}
