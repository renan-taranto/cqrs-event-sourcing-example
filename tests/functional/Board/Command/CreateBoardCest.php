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
 * Class CreateBoardCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateBoardCest
{
    public function it_creates_a_board(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[
            'id' => '9247f601-e0f1-4981-b4a3-8249ffe988af',
            'title' => 'Sprint 2'
        ]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'id' => 'This value should not be blank.',
            'title' => 'This field is missing.'
        ]]);
    }

    public function it_returns_bad_request_when_id_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[
            'id' => '132456',
            'title' => 'Sprint 2'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'id' => 'This is not a valid UUID.'
        ]]);
    }

    public function it_returns_bad_request_when_id_is_already_in_use(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[
            'id' => 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392',
            'title' => 'Sprint 2'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'id' => 'This id is already in use.'
        ]]);
    }

    public function it_returns_bad_request_when_id_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[
            'id' => '',
            'title' => 'Sprint 2'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'id' => 'This value should not be blank.'
        ]]);
    }

    public function it_returns_bad_request_when_title_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[
            'id' => '9247f601-e0f1-4981-b4a3-8249ffe988af',
            'title' => ''
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'title' => 'This value should not be blank.'
        ]]);
    }

    public function it_returns_bad_request_when_title_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards',[
            'id' => '9247f601-e0f1-4981-b4a3-8249ffe988af',
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => [
            'title' => 'This value is too long. It should have 50 characters or less.'
        ]]);
    }
}
