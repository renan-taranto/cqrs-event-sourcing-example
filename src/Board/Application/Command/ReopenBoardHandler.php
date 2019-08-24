<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\Command;

use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Domain\Exception\BoardNotFound;

/**
 * Class ReopenBoardHandler
 * @package Taranto\ListMaker\Board\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ReopenBoardHandler
{
    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * ReopenBoardHandler constructor.
     * @param BoardRepository $boardRepository
     */
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * @param ReopenBoard $command
     * @throws BoardNotFound
     */
    public function __invoke(ReopenBoard $command): void
    {
        $board = $this->boardRepository->get($command->aggregateId());
        if ($board === null) {
            throw BoardNotFound::withBoardId($command->aggregateId());
        }

        $board->reopen();
        $this->boardRepository->save($board);
    }
}
