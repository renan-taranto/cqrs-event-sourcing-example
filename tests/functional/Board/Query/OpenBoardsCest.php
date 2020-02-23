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
 * Class OpenBoardsCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class OpenBoardsCest
{
    public function it_returns_all_open_boards(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392',
                'title' => 'To-Dos',
                'open' => true,
                'lists' => [
                    [
                        'id' => '197c76a8-dcd9-473e-afd8-3ea6556484f3',
                        'title' => 'To Do',
                        'items' => []
                    ],
                    [
                        'id' => '78a03a97-6643-4940-853b-0c89ada22bf2',
                        'title' => 'Doing',
                        'items' => []
                    ],
                    [
                        'id' => 'c69fdf67-353d-4196-b8e8-2d8f1d475208',
                        'title' => 'Done',
                        'items' => []
                    ]
                ],
                'archivedLists' => [
                    [
                        'id' => 'd33a1a8e-5933-4fbc-b60c-0f37d201b2b4',
                        'title' => 'Reviewing',
                        'items' => []
                    ]
                ]
            ],
            [
                'boardId' => '4b2baa7e-315b-41cc-857b-8852619d230b',
                'title' => 'Tasks',
                'open' => true,
                'lists' => [],
                'archivedLists' => []
            ],
            [
                'boardId' => 'c62abbe1-fb68-4e6d-a6a3-b41aee8564c8',
                'title' => 'Issues',
                'open' => true,
                'lists' => [],
                'archivedLists' => []
            ]
        ]);
    }
}
