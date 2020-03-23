<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Board\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Application\Command\CreateBoard;
use Taranto\ListMaker\Board\Application\Command\CreateBoardHandler;
use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class CreateBoardHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateBoardHandlerTest extends Unit
{
    /**
     * @var BoardRepository
     */
    private $repository;

    /**
     * @var CreateBoardHandler
     */
    private $handler;

    /**
     * @var CreateBoard
     */
    private $command;

    /**
     * @var Board
     */
    private $board;

    protected function _before(): void
    {
        $this->repository = \Mockery::spy(BoardRepository::class);
        $this->handler = new CreateBoardHandler($this->repository);

        $boardId = BoardId::generate();
        $title = Title::fromString('To-Dos');
        $this->command = CreateBoard::request((string) $boardId, ['title' => (string) $title]);
        $this->board = Board::create($boardId, $title);
    }

    /**
     * @test
     */
    public function it_creates_a_board(): void
    {
        ($this->handler)($this->command);

        $this->repository->shouldHaveReceived('save')->with(isEqual::equalTo($this->board));
    }
}
