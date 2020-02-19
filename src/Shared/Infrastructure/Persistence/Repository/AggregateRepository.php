<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Persistence\Repository;

use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRoot;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore\EventStore;

/**
 * Class AggregateRepository
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\Repository
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AggregateRepository
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * ListRepository constructor.
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param AggregateRoot $aggregateRoot
     */
    public function save(AggregateRoot $aggregateRoot): void
    {
        $this->eventStore->commit(
            $aggregateRoot->aggregateId(),
            $aggregateRoot->popRecordedEvents(),
            $aggregateRoot->aggregateVersion()
        );
    }

    /**
     * @param string $aggregateClass
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateRoot|null
     */
    public function get(string $aggregateClass, IdentifiesAggregate $aggregateId): ?AggregateRoot
    {
        $aggregateHistory = $this->eventStore->aggregateHistoryFor($aggregateId);
        if (count($aggregateHistory) === 0) {
            return null;
        }

        if (!is_a($aggregateClass, AggregateRoot::class, true)) {
            throw new \InvalidArgumentException("'{$aggregateClass}' must be an AggregateRoot");
        }
        return $aggregateClass::reconstituteFrom($aggregateHistory);
    }
}
