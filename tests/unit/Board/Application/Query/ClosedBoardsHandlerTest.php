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

use Codeception\Specify;
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
    use Specify;

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
        $this->closedBoardsData = [
            new BoardData((string) BoardId::generate(), 'To-Dos', false),
            new BoardData((string) BoardId::generate(), 'Jobs', false)
        ];

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
                $this->boardFinder->shouldReceive('closedBoards')->andReturn($this->closedBoardsData);

                $boardsData = ($this->closedBoardsHandler)(new ClosedBoards());

                expect($boardsData)->equals($this->closedBoardsData);
            });
        });
    }
}
