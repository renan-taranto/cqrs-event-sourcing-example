<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Functional\Item\Command;

use Codeception\Util\HttpCode;
use Taranto\ListMaker\Tests\FunctionalTester;

/**
 * Class RestoreItemCest
 * @package Taranto\ListMaker\Tests\Functional\Item\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class RestoreItemCest
{
    public function it_restores_an_item(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/a7bb5c80-0b83-41f2-83cc-b1477a298434/restore');
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_item_is_not_archived(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/c8f94b93-a41d-490d-85e0-47990bc4792f/restore');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'The item is not archived.']]);
    }

    public function it_returns_not_found_when_item_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/123456/restore');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
