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
 * Class AddItemCest
 * @package Taranto\ListMaker\Tests\Functional\Item\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AddItemCest
{
    public function it_adds_an_item(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Feature: Items',
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_adds_an_item_at_a_given_position(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Feature: Items',
            'position' => 2,
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'id' => 'This value should not be blank.',
            'title' => 'This value should not be blank.',
            'listId' => 'This value should not be blank.'
        ]]);
    }

    public function it_returns_bad_request_when_id_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items',[
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514',
            'title' => 'Feature: Items',
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'This is not a valid UUID.']]);
    }

    public function it_returns_bad_request_when_id_is_already_in_use(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items',[
            'id' => 'c8f94b93-a41d-490d-85e0-47990bc4792f',
            'title' => 'Feature: Items',
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'Item id already in use.']]);
    }

    public function it_returns_bad_request_when_title_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => '',
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_title_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value is too long. It should have 50 characters or less.']]);
    }

    public function it_returns_bad_request_when_position_is_less_than_zero(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Feature: Items',
            'position' => -1,
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'This value should be greater than or equal to 0.']]);
    }

    public function it_returns_bad_request_when_position_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Feature: Items',
            'position' => 4,
            'listId' => '197c76a8-dcd9-473e-afd8-3ea6556484f3'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['position' => 'Invalid position.']]);
    }

    public function it_returns_bad_request_when_list_id_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Feature: Items',
            'listId' => ''
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['listId' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_list_is_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/items', [
            'id' => '2eec1c8c-8ea8-44e2-9df0-4dfb774e514c',
            'title' => 'Feature: Items',
            'listId' => 'bc567412-8395-46e8-8b45-9c6a664b628a'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['listId' => 'List not found.']]);
    }
}
