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
 * Interface EventStore
 * @package Taranto\ListMaker\Infrastructure\Persistence\EventStore
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
