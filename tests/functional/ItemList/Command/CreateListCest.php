<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Functional\ItemList\Command;

use Codeception\Util\HttpCode;
use Taranto\ListMaker\Tests\FunctionalTester;

/**
 * Class CreateListCest
 * @package Taranto\ListMaker\Tests\Functional\ItemList\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateListCest
{
    public function it_creates_a_list(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => 'Backlog',
            'position' => 3,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'id' => 'This value should not be blank.',
            'title' => 'This field is missing.',
            'position' => 'This field is missing.',
            'boardId' => 'This field is missing.'
        ]]);
    }

    public function it_returns_bad_request_when_id_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170a',
            'title' => 'Backlog',
            'position' => 3,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'This is not a valid UUID.']]);
    }

    public function it_returns_bad_request_when_id_is_already_in_use(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => '197c76a8-dcd9-473e-afd8-3ea6556484f3',
            'title' => 'Backlog',
            'position' => 3,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'List id already in use.']]);
    }

    public function it_returns_bad_request_when_title_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => '',
            'position' => 3,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_title_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'position' => 3,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value is too long. It should have 50 characters or less.']]);
    }

    public function it_returns_bad_request_when_position_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => 'Backlog',
            'position' => null,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_position_is_less_than_zero(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => 'Backlog',
            'position' => -1,
            'boardId' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'This value should be greater than 0.']]);
    }

    public function it_returns_bad_request_when_board_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => 'Backlog',
            'position' => 3,
            'boardId' => ''
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['boardId' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_board_is_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists',[
            'id' => 'c505ef6a-1232-49ba-9c7c-ddfa5f8b5170',
            'title' => 'Backlog',
            'position' => 3,
            'boardId' => 'ecc07880-671b-4b10-a4b4-81455d7fb141'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['boardId' => 'Board not found.']]);
    }
}
