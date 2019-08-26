<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Board\Application\Query;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Application\Query\ClosedBoards;
use Taranto\ListMaker\Board\Application\Query\ClosedBoardsHandler;
use Taranto\ListMaker\Board\Application\Query\Data\BoardData;
use Taranto\ListMaker\Board\Application\Query\Data\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;

/**
 * Class ClosedBoardsHandlerTest
 * @package Taranto\ListMaker\Tests\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ClosedBoardsHandlerTest extends Unit
{
    /**
     * @var BoardData[]
     */
    private $closedBoardsData;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * @var ClosedBoardsHandler
     */
    private $closedBoardsHandler;

    protected function _before(): void
    {
        $this->boardFinder = \Mockery::mock(BoardFinder::class);
        $this->closedBoardsHandler = new ClosedBoardsHandler($this->boardFinder);

        $this->closedBoardsData = [
            new BoardData((string) BoardId::generate(), 'To-Dos', false),
            new BoardData((string) BoardId::generate(), 'Jobs', false)
        ];
    }

    /**
     * @test
     */
    public function it_returns_all_closed_boards(): void
    {
        $this->boardFinder->shouldReceive('closedBoards')->andReturn($this->closedBoardsData);

        $boardsData = ($this->closedBoardsHandler)(new ClosedBoards());

        expect($boardsData)->equals($this->closedBoardsData);
    }
}
