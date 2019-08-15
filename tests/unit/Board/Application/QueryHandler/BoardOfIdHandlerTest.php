<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\Board\Application\QueryHandler;

use Codeception\Specify;
use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Application\QueryHandler\BoardOfIdHandler;
use Taranto\ListMaker\Board\Application\QueryHandler\Data\BoardData;
use Taranto\ListMaker\Board\Domain\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Query\BoardOfId;

/**
 * Class BoardOfIdHandlerTest
 * @package Taranto\ListMaker\Tests\unit\Board\Application\QueryHandler
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardOfIdHandlerTest extends Unit
{
    use Specify;

    /**
     * @var string
     */

    private $boardId;
    /**
     * @var array
     */
    private $normalizedBoard;

    /**
     * @var BoardData
     */
    private $boardData;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * @var BoardOfIdHandler
     */
    private $boardOfIdHandler;

    protected function _before(): void
    {
        $this->boardId = (string) BoardId::generate();
        $this->normalizedBoard = ['boardId'  => $this->boardId, 'title' => 'To-Dos'];
        $this->boardData = new BoardData($this->normalizedBoard['boardId'], $this->normalizedBoard['title']);
    }

    /**
     * @test
     */
    public function boardOfId(): void
    {
        $this->describe('Query Board of Id', function () {
            $this->beforeSpecify(function () {
                $this->boardFinder = \Mockery::mock(BoardFinder::class);
                $this->boardOfIdHandler = new BoardOfIdHandler($this->boardFinder);
            });

            $this->should('return a board with the given id', function () {
                $this->boardFinder->shouldReceive('boardOfId')
                    ->with($this->boardId)
                    ->andReturn($this->normalizedBoard);

                $boardData = ($this->boardOfIdHandler)(new BoardOfId($this->boardId));

                expect($boardData)->equals($this->boardData);
            });

            $this->should('return null when board not found', function () {
                $this->boardFinder->shouldReceive('boardOfId')
                    ->with($this->boardId)
                    ->andReturn(null);

                $boardData = ($this->boardOfIdHandler)(new BoardOfId($this->boardId));

                expect($boardData)->null();
            });
        });
    }
}
