<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Functional\Board\Query;

use Codeception\Util\HttpCode;
use Taranto\ListMaker\Tests\FunctionalTester;

/**
 * Class BoardsOverviewCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardsOverviewCest
{
    public function it_returns_an_overview_of_all_boards(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseEquals(json_encode($this->allBoardsOverview()));
    }

    public function it_returns_an_overview_of_open_boards(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards?open=true');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseEquals(json_encode($this->openBoardsOverview()));
    }

    public function it_returns_an_overview_of_closed_boards(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards?open=false');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseEquals(json_encode($this->closedBoardsOverview()));
    }

    /**
     * @return array
     */
    private function allBoardsOverview(): array
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
    private function openBoardsOverview(): array
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
    private function closedBoardsOverview(): array
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
