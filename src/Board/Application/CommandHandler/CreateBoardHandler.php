<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\CommandHandler;

use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Domain\Command\CreateBoard;

/**
 * Class CreateBoardHandler
 * @package Taranto\ListMaker\Board\Application\CommandHandler
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CreateBoardHandler
{
    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * CreateBoardHandler constructor.
     * @param BoardRepository $boardRepository
     */
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * @param CreateBoard $command
     */
    public function __invoke(CreateBoard $command): void
    {
        $this->boardRepository->save(Board::create($command->aggregateId(), $command->title()));
    }
}
