<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\functional\ItemList\Command;

use Codeception\Util\HttpCode;
use Taranto\ListMaker\Tests\FunctionalTester;

/**
 * Class MoveListCest
 * @package Taranto\ListMaker\Tests\functional\ItemList\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MoveListCest
{
    private const LIST_ID = '197c76a8-dcd9-473e-afd8-3ea6556484f3';
    private const BOARD_ID = '4b2baa7e-315b-41cc-857b-8852619d230b';

    public function it_moves_the_list_in_the_same_board(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/move', ['position' => 2, 'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392']);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_moves_the_list_to_another_board(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/move', ['position' => 0, 'boardId' => self::BOARD_ID]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/move', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'This value should not be blank.', 'boardId' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_position_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/move', ['position' => 1, 'boardId' => self::BOARD_ID]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'Invalid position.']]);
    }

    public function it_returns_bad_request_when_board_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/move', ['position' => 1, 'boardId' => '12345']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['boardId' => 'Board not found.']]);
    }

    public function it_returns_bad_request_when_list_is_archived(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/d33a1a8e-5933-4fbc-b60c-0f37d201b2b4/move', ['position' => 0, 'boardId' => self::BOARD_ID]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'Unable to move an archived list.']]);
    }

    public function it_returns_not_found_when_list_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/123456/move');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
