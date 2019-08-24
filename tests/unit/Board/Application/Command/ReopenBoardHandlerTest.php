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

use Codeception\Specify;
use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Application\Command\ReopenBoardHandler;
use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Application\Command\ReopenBoard;
use Taranto\ListMaker\Board\Domain\Exception\BoardNotFound;

/**
 * Class ReopenBoardHandlerTest
 * @package Taranto\ListMaker\Tests\Board\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReopenBoardHandlerTest extends Unit
{
    use Specify;

    /**
     * @var BoardRepository
     */
    private $repository;

    /**
     * @var ReopenBoardHandler
     */
    private $handler;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var ReopenBoard
     */
    private $command;

    protected function _before(): void
    {
        $this->board = \Mockery::spy(Board::class);
        $this->boardId = BoardId::generate();
        $this->command = ReopenBoard::request((string) $this->boardId);
    }

    /**
     * @test
     */
    public function reopenBoard(): void
    {
        $this->describe('Reopen Board', function() {
            $this->beforeSpecify(function () {
                $this->repository = \Mockery::mock(BoardRepository::class);
                $this->handler = new ReopenBoardHandler($this->repository);
            });
            $this->should('reopen the Board and save it', function () {
                $this->repository->shouldReceive('get')
                    ->with(isEqual::equalTo($this->boardId))
                    ->andReturn($this->board);
                $this->repository->shouldReceive('save')
                    ->with(isEqual::equalTo($this->board));

                ($this->handler)($this->command);

                $this->board->shouldHaveReceived('reopen');
            });
            $this->should("throw exception when Board not found", function() {
                $this->repository->shouldReceive('get')
                    ->with(IsEqual::equalTo($this->boardId))
                    ->andReturn(null);

                $this->expectExceptionObject(BoardNotFound::withBoardId($this->boardId));

                ($this->handler)($this->command);
            });
        });
    }
}
