<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Functional\Board\Command;

use Codeception\Util\HttpCode;
use Taranto\ListMaker\Tests\FunctionalTester;

/**
 * Class ReopenBoardCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReopenBoardCest
{
    public function it_reopens_the_board(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/d81805d3-a350-4ef0-81f0-9eb122b4c1ea/reopen');
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_not_found_when_board_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/12345/reopen');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
