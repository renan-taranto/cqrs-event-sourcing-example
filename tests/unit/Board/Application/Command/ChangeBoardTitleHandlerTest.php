<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Board\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Application\Command\ChangeBoardTitle;
use Taranto\ListMaker\Board\Application\Command\ChangeBoardTitleHandler;
use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Domain\Exception\BoardNotFound;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ChangeBoardTitleHandlerTest
 * @package Taranto\ListMaker\Tests\Board\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeBoardTitleHandlerTest extends Unit
{
    /**
     * @var BoardRepository
     */
    private $repository;

    /**
     * @var ChangeBoardTitleHandler
     */
    private $handler;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var ChangeBoardTitle
     */
    private $command;

    protected function _before(): void
    {
        $this->repository = \Mockery::mock(BoardRepository::class);
        $this->handler = new ChangeBoardTitleHandler($this->repository);

        $this->board = \Mockery::spy(Board::class);
        $this->title = Title::fromString('Tasks');
        $this->boardId = BoardId::generate();
        $this->command = ChangeBoardTitle::request((string) $this->boardId, ['title' => (string) $this->title]);
    }

    /**
     * @test
     */
    public function it_changes_the_board_title(): void
    {
        $this->repository->shouldReceive('get')
            ->with(isEqual::equalTo($this->boardId))
            ->andReturn($this->board);
        $this->repository->shouldReceive('save')->with($this->board);

        ($this->handler)($this->command);

        $this->board->shouldHaveReceived('changeTitle')->with(isEqual::equalTo((string) $this->title));
    }

    /**
     * @test
     */
    public function it_throws_when_board_not_found(): void
    {
        $this->repository->shouldReceive('get')
            ->with(IsEqual::equalTo($this->boardId))
            ->andReturn(null);

        $this->expectExceptionObject(BoardNotFound::withBoardId($this->boardId));

        ($this->handler)($this->command);
    }
}
