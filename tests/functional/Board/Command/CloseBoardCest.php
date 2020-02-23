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
 * Class CloseBoardCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CloseBoardCest
{
    public function it_closes_the_board(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/b6e7cfd0-ae2b-44ee-9353-3e5d95e57392/close');
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_not_found_when_board_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/12345/close');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
