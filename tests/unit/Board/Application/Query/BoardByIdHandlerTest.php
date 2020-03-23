<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Board\Application\Query;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Application\Query\BoardById;
use Taranto\ListMaker\Board\Application\Query\BoardByIdHandler;
use Taranto\ListMaker\Board\Application\Query\Finder\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;

/**
 * Class BoardByIdHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardByIdHandlerTest extends Unit
{
    /**
     * @var string
     */
    private $boardId;

    /**
     * @var array
     */
    private $boardData;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * @var BoardByIdHandler
     */
    private $handler;

    protected function _before(): void
    {
        $this->boardFinder = \Mockery::mock(BoardFinder::class);
        $this->handler = new BoardByIdHandler($this->boardFinder);

        $this->boardId = (string) BoardId::generate();
        $this->boardData = ['id' => $this->boardId, 'title' => 'To-Dos', 'open' => true, 'lists' => [], 'archivedLists' => []];
    }

    /**
     * @test
     */
    public function it_returns_a_board_with_the_given_id(): void
    {
        $this->boardFinder->shouldReceive('byId')
            ->with($this->boardId)
            ->andReturn($this->boardData);

        $boardData = ($this->handler)(new BoardById(['boardId' => $this->boardId]));

        expect($boardData)->equals($this->boardData);
    }

    /**
     * @test
     */
    public function it_returns_null_when_board_not_found(): void
    {
        $this->boardFinder->shouldReceive('byId')
            ->with($this->boardId)
            ->andReturn(null);

        $boardData = ($this->handler)(new BoardById(['boardId' => $this->boardId]));

        expect($boardData)->null();
    }
}
