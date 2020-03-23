<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\BoardOverviewFinder;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class BoardOverviewFinderTest
 * @package Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardOverviewFinderTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var BoardOverviewFinder
     */
    private $boardOverviewFinder;

    protected function _before(): void
    {
        $this->boardOverviewFinder = $this->tester->grabService('test.service_container')->get(BoardOverviewFinder::class);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_a_single_board(): void
    {
        $singleBoardOverview = $this->singleBoardOverview();

        $result = $this->boardOverviewFinder->byBoardId($singleBoardOverview['id']);

        expect($result)->equals($singleBoardOverview);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_all_boards(): void
    {
        $allBoardsOverview = $this->allBoardsOverview();

        $result = $this->boardOverviewFinder->all();

        expect($result)->equals($allBoardsOverview);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_all_open_boards(): void
    {
        $openBoardsOverview = $this->openBoardsOverview();

        $result = $this->boardOverviewFinder->allOpenBoards();

        expect($result)->equals($openBoardsOverview);
    }

    /**
     * @test
     */
    public function it_returns_an_overview_of_all_closed_boards(): void
    {
        $closedBoardsOverview = $this->closedBoardsOverview();

        $result = $this->boardOverviewFinder->allClosedBoards();

        expect($result)->equals($closedBoardsOverview);
    }

    /**
     * @return array
     */
    public function singleBoardOverview(): array
    {
        return [
            "id" => "b6e7cfd0-ae2b-44ee-9353-3e5d95e57392",
            "title" => "To-Dos",
            "open" => true
        ];
    }

    /**
     * @return array
     */
    public function allBoardsOverview(): array
    {
        return [
            [
                'id' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392',
                'title' => 'To-Dos',
                'open' => true
            ],
            [
                'id' => '4b2baa7e-315b-41cc-857b-8852619d230b',
                'title' => 'Tasks',
                'open' => true
            ],
            [
                "id" => "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
                "title" => "Jobs",
                "open" => false
            ],
            [
                "id" => "37d22c48-17f7-4849-8fb2-dc67f29496f1",
                "title" => "Backlog",
                "open" => false
            ],
            [
                'id' => 'c62abbe1-fb68-4e6d-a6a3-b41aee8564c8',
                'title' => 'Issues',
                'open' => true
            ]
        ];
    }

    /**
     * @return array
     */
    public function openBoardsOverview(): array
    {
        return [
            [
                'id' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392',
                'title' => 'To-Dos',
                'open' => true
            ],
            [
                'id' => '4b2baa7e-315b-41cc-857b-8852619d230b',
                'title' => 'Tasks',
                'open' => true
            ],
            [
                'id' => 'c62abbe1-fb68-4e6d-a6a3-b41aee8564c8',
                'title' => 'Issues',
                'open' => true
            ]
        ];
    }

    /**
     * @return array
     */
    public function closedBoardsOverview(): array
    {
        return [
            [
                "id" => "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
                "title" => "Jobs",
                "open" => false
            ],
            [
                "id" => "37d22c48-17f7-4849-8fb2-dc67f29496f1",
                "title" => "Backlog",
                "open" => false
            ]
        ];
    }
}
