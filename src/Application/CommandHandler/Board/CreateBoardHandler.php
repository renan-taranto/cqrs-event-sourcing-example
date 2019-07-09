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

use Taranto\ListMaker\Domain\Model\Board\Board;
use Taranto\ListMaker\Domain\Model\Board\BoardRepository;
use Taranto\ListMaker\Domain\Model\Board\Command\CreateBoard;

/**
 * Class CreateBoardHandler
 * @package Taranto\ListMaker\Application\CommandHandler\Board
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
