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
 * Class ReorderItemCest
 * @package Taranto\ListMaker\Tests\Functional\Item\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReorderItemCest
{
    private const ITEM_ID = 'c8f94b93-a41d-490d-85e0-47990bc4792f';

    public function it_reorders_the_item(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/reorder', ['toPosition' => 2]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/reorder', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['toPosition' => 'This field is missing.']]);
    }

    public function it_returns_bad_request_when_to_position_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/reorder', ['toPosition' => 3]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['toPosition' => 'Invalid position.']]);
    }

    public function it_returns_bad_request_when_item_is_archived(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/a7bb5c80-0b83-41f2-83cc-b1477a298434/reorder', ['toPosition' => 2]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'Unable to reorder an archived item.']]);
    }

    public function it_returns_not_found_when_item_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/123456/reorder');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
