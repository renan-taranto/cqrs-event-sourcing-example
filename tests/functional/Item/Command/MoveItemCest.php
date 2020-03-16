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
 * Class MoveItemCest
 * @package Taranto\ListMaker\Tests\Functional\Item\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MoveItemCest
{
    private const ITEM_ID = 'c8f94b93-a41d-490d-85e0-47990bc4792f';
    private const LIST_ID = '78a03a97-6643-4940-853b-0c89ada22bf2';

    public function it_moves_the_item_in_the_same_list(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/move', ['position' => 2, 'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3']);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_moves_the_item_to_another_list(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/move', ['position' => 0, 'listId' => self::LIST_ID]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/move', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'This field is missing.', 'listId' => 'This field is missing.']]);
    }

    public function it_returns_bad_request_when_position_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/move', ['position' => 1, 'listId' => self::LIST_ID]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'Invalid position.']]);
    }

    public function it_returns_bad_request_when_list_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/move', ['position' => 1, 'listId' => '12345']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['listId' => 'List not found.']]);
    }

    public function it_returns_bad_request_when_item_is_archived(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/a7bb5c80-0b83-41f2-83cc-b1477a298434/move', ['position' => 0, 'listId' => self::LIST_ID]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'Unable to move an archived item.']]);
    }

    public function it_returns_not_found_when_item_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/123456/move');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
