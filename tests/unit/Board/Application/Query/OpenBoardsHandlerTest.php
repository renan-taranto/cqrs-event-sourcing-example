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
use Taranto\ListMaker\Board\Application\Query\Data\BoardData;
use Taranto\ListMaker\Board\Application\Query\OpenBoards;
use Taranto\ListMaker\Board\Application\Query\OpenBoardsHandler;
use Taranto\ListMaker\Board\Domain\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;

/**
 * Class OpenBoardsHandlerTest
 * @package Taranto\ListMaker\Tests\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class OpenBoardsHandlerTest extends Unit
{
    use Specify;

    /**
     * @var array
     */
    private $normalizedBoards;

    /**
     * @var BoardData[]
     */
    private $openBoardsData;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * @var OpenBoardsHandler
     */
    private $openBoardsHandler;

    protected function _before(): void
    {
        $this->normalizedBoards = [
            ['boardId' => (string) BoardId::generate(), 'title' => 'To-Dos', 'isOpen' => true],
            ['boardId' => (string) BoardId::generate(), 'title' => 'Jobs', 'isOpen' => true]
        ];
        $this->openBoardsData = array_map(function ($board) {
            return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
        }, $this->normalizedBoards);

        $this->boardFinder = \Mockery::mock(BoardFinder::class);
        $this->openBoardsHandler = new OpenBoardsHandler($this->boardFinder);
    }

    /**
     * @test
     */
    public function openBoards(): void
    {
        $this->describe('Query Open Boards', function () {
            $this->should('return all open boards', function () {
                $this->boardFinder->shouldReceive('openBoards')->andReturn($this->normalizedBoards);

                $boardsData = ($this->openBoardsHandler)(new OpenBoards());

                expect($boardsData)->equals($this->openBoardsData);
            });
        });
    }
}
