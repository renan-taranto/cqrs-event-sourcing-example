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
use Taranto\ListMaker\Board\Application\Query\Data\BoardFinder;
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
        $this->openBoardsData = [
            new BoardData((string) BoardId::generate(), 'To-Dos', true),
            new BoardData((string) BoardId::generate(), 'Jobs', true)
        ];

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
                $this->boardFinder->shouldReceive('openBoards')->andReturn($this->openBoardsData);

                $boardsData = ($this->openBoardsHandler)(new OpenBoards());

                expect($boardsData)->equals($this->openBoardsData);
            });
        });
    }
}
