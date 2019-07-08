<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Common;

/**
 * Class AggregateHistory
 * @package Taranto\ListMaker\Domain\Model\Common
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class AggregateHistory extends DomainEvents
{
    /**
     * AggregateHistory constructor.
     * @param IdentifiesAggregate $aggregateId
     * @param array $events
     * @throws CorruptAggregateHistory
     */
    public function __construct(IdentifiesAggregate $aggregateId, array $events)
    {
        foreach($events as $event) {
            /** @var $event DomainEvent */
            if(!$event->aggregateId()->equals($aggregateId)) {
                throw new CorruptAggregateHistory();
            }
        }
        parent::__construct($events);
    }
}
