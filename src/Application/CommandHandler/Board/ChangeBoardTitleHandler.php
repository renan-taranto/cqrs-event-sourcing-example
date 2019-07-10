<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Application\CommandHandler\Board;

use Taranto\ListMaker\Domain\Model\Board\BoardRepository;
use Taranto\ListMaker\Domain\Model\Board\Command\ChangeBoardTitle;
use Taranto\ListMaker\Domain\Model\Board\Exception\BoardNotFound;

/**
 * Class ChangeBoardTitleHandler
 * @package Taranto\ListMaker\Application\CommandHandler\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ChangeBoardTitleHandler
{
    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * ChangeBoardTitleHandler constructor.
     * @param BoardRepository $boardRepository
     */
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * @param ChangeBoardTitle $command
     * @throws BoardNotFound
     */
    public function __invoke(ChangeBoardTitle $command): void
    {
        $board = $this->boardRepository->get($command->aggregateId());
        if ($board === null) {
            throw BoardNotFound::withBoardId($command->aggregateId());
        }

        $board->changeTitle($command->title());
        $this->boardRepository->save($board);
    }
}
