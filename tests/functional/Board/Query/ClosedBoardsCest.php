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
 * Class ClosedBoardsCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ClosedBoardsCest
{
    public function it_returns_all_closed_boards(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards/closed');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            [
                "id" => "d81805d3-a350-4ef0-81f0-9eb122b4c1ea",
                "title" => "Jobs",
                "open" => false,
                "lists" => [],
                "archivedLists" => []
            ],
            [
                "id" => "37d22c48-17f7-4849-8fb2-dc67f29496f1",
                "title" => "Backlog",
                "open" => false,
                "lists" => [],
                "archivedLists" => []
            ]
        ]);
    }
}