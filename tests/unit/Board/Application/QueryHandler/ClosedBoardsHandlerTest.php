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
use Taranto\ListMaker\Board\Application\QueryHandler\ClosedBoardsHandler;
use Taranto\ListMaker\Board\Application\QueryHandler\Data\BoardData;
use Taranto\ListMaker\Board\Domain\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Query\ClosedBoards;

/**
 * Class ClosedBoardsHandlerTest
 * @package Taranto\ListMaker\Tests\unit\Board\Application\QueryHandler
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ClosedBoardsHandlerTest extends Unit
{
    use Specify;

    /**
     * @var array
     */
    private $normalizedBoards;

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
        $this->normalizedBoards = [
            ['boardId' => (string) BoardId::generate(), 'title' => 'To-Dos', 'isOpen' => false],
            ['boardId' => (string) BoardId::generate(), 'title' => 'Jobs', 'isOpen' => false]
        ];
        $this->closedBoardsData = array_map(function ($board) {
            return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
        }, $this->normalizedBoards);

        $this->boardFinder = \Mockery::mock(BoardFinder::class);
        $this->closedBoardsHandler = new ClosedBoardsHandler($this->boardFinder);
    }

    /**
     * @test
     */
    public function closedBoards(): void
    {
        $this->describe('Query Closed Boards', function () {
            $this->should('return all closed boards', function () {
                $this->boardFinder->shouldReceive('closedBoards')->andReturn($this->normalizedBoards);

                $boardsData = ($this->closedBoardsHandler)(new ClosedBoards());

                expect($boardsData)->equals($this->closedBoardsData);
            });
        });
    }
}
