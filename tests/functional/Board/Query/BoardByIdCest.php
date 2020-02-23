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
 * Class BoardByIdCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardByIdCest
{
    public function it_returns_the_board_having_the_given_id(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards/4b2baa7e-315b-41cc-857b-8852619d230b');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'id' => '4b2baa7e-315b-41cc-857b-8852619d230b',
            'title' => 'Tasks',
            'open' => true,
            'lists' => [],
            'archivedLists' => []
        ]);
    }

    public function it_returns_not_found_when_board_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGET('/boards/12345');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
