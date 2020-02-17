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
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Repository\AggregateRepository;

/**
 * Class BoardRepository
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Repository
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardRepository implements BoardRepositoryInterface
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * BoardRepository constructor.
     * @param AggregateRepository $aggregateRepository
     */
    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    /**
     * @param Board $board
     */
    public function save(Board $board): void
    {
        $this->aggregateRepository->save($board);
    }

    /**
     * @param BoardId $boardId
     * @return Board|null
     */
    public function get(BoardId $boardId): ?Board
    {
        return $this->aggregateRepository->get(Board::class, $boardId);
    }
}
