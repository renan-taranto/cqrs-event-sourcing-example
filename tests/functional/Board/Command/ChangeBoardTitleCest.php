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
 * Class ChangeBoardTitleCest
 * @package Taranto\ListMaker\Tests\Functional\Board\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeBoardTitleCest
{
    private const BOARD_ID = 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392';

    public function it_changes_the_title(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/' . self::BOARD_ID . '/change-title',[
            'title' => 'Testing'
        ]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/' . self::BOARD_ID . '/change-title', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This field is missing.']]);
    }

    public function it_returns_bad_request_when_title_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/' . self::BOARD_ID . '/change-title', ['title' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_title_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/' . self::BOARD_ID . '/change-title', [
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value is too long. It should have 50 characters or less.']]);
    }

    public function it_returns_not_found_when_board_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/boards/123456/change-title', [
            'title' => 'Testing'
        ]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
