<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Infrastructure\Persistence\Repository;

use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\BoardRepository as BoardRepositoryInterface;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore\EventStore;

/**
 * Class BoardRepository
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Repository
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardRepository implements BoardRepositoryInterface
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * BoardRepository constructor.
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param Board $board
     */
    public function save(Board $board): void
    {
        $this->eventStore->commit($board->popRecordedEvents(), $board->aggregateVersion());
    }

    /**
     * @param BoardId $boardId
     * @return Board|null
     */
    public function get(BoardId $boardId): ?Board
    {
        $aggregateHistory = $this->eventStore->aggregateHistoryFor($boardId);
        if (count($aggregateHistory) === 0) {
            return null;
        }

        return Board::reconstituteFrom($aggregateHistory);
    }
}
