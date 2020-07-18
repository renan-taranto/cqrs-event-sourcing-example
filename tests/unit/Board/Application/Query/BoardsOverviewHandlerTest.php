<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\Board\Application\Query;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Application\Query\BoardsOverview;
use Taranto\ListMaker\Board\Application\Query\BoardsOverviewHandler;
use Taranto\ListMaker\Board\Application\Query\Finder\BoardOverviewFinder;

/**
 * Class BoardsOverviewHandlerTest
 * @package Taranto\ListMaker\Tests\unit\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardsOverviewHandlerTest extends Unit
{
    /**
     * @var BoardOverviewFinder
     */
    private $boardOverviewFinder;

    /**
     * @var BoardsOverviewHandler
     */
    private $boardsOverviewHandler;

    protected function _before(): void
    {
        $this->boardOverviewFinder = \Mockery::mock(BoardOverviewFinder::class);
        $this->boardsOverviewHandler = new BoardsOverviewHandler($this->boardOverviewFinder);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_all_boards(): void
    {
        $allBoardsOverview = $this->allBoardsOverview();
        $this->boardOverviewFinder->shouldReceive('all')
            ->andReturn($allBoardsOverview);

        $queryResult = ($this->boardsOverviewHandler)(new BoardsOverview());
        expect($queryResult)->equals($allBoardsOverview);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_open_boards(): void
    {
        $openBoardsOverview = $this->openBoardsOverview();
        $this->boardOverviewFinder->shouldReceive('allOpenBoards')
            ->andReturn($openBoardsOverview);

        $queryResult = ($this->boardsOverviewHandler)(new BoardsOverview(true));
        expect($queryResult)->equals($openBoardsOverview);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_closed_boards(): void
    {
        $closedBoardsOverview = $this->closedBoardsOverview();
        $this->boardOverviewFinder->shouldReceive('allClosedBoards')
            ->andReturn($closedBoardsOverview);

        $queryResult = ($this->boardsOverviewHandler)(new BoardsOverview(false));
        expect($queryResult)->equals($closedBoardsOverview);
    }

    /**
     * @return array
     */
    private function allBoardsOverview(): array
    {
        return [
            [
                'id' => '4b2baa7e-315b-41cc-857b-8852619d230b',
                'title' => 'Tasks',
                'open' => true
            ],
            [
                "id" => "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
                "title" => "Jobs",
                "open" => false
            ]
        ];
    }

    /**
     * @return array
     */
    private function openBoardsOverview(): array
    {
        return [
            [
                'id' => '4b2baa7e-315b-41cc-857b-8852619d230b',
                'title' => 'Tasks',
                'open' => true
            ]
        ];
    }

    /**
     * @return array
     */
    private function closedBoardsOverview(): array
    {
        return [
            [
                "id" => "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
                "title" => "Jobs",
                "open" => false
            ]
        ];
    }
}