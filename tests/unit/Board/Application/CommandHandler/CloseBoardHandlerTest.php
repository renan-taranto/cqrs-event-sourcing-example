<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Board\Application\CommandHandler;

use Codeception\Specify;
use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Application\CommandHandler\CloseBoardHandler;
use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Domain\Command\CloseBoard;
use Taranto\ListMaker\Board\Domain\Exception\BoardNotFound;

/**
 * Class CloseBoardHandlerTest
 * @package Taranto\ListMaker\Tests\Board\Application\CommandHandler
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CloseBoardHandlerTest extends Unit
{
    use Specify;

    /**
     * @var BoardRepository
     */
    private $repository;

    /**
     * @var CloseBoardHandler
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
     * @var CloseBoard
     */
    private $command;

    protected function _before(): void
    {
        $this->board = \Mockery::spy(Board::class);
        $this->boardId = BoardId::generate();
        $this->command = CloseBoard::request((string) $this->boardId);
    }

    /**
     * @test
     */
    public function closeBoard(): void
    {
        $this->describe('Close Board', function() {
            $this->beforeSpecify(function () {
                $this->repository = \Mockery::mock(BoardRepository::class);
                $this->handler = new CloseBoardHandler($this->repository);
            });
            $this->should('close the Board and save it', function () {
                $this->repository->shouldReceive('get')
                    ->with(isEqual::equalTo($this->boardId))
                    ->andReturn($this->board);
                $this->repository->shouldReceive('save')
                    ->with(isEqual::equalTo($this->board));

                ($this->handler)($this->command);

                $this->board->shouldHaveReceived('close');
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
