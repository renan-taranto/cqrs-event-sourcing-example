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
 * Class ChangeListTitleCest
 * @package Taranto\ListMaker\Tests\Functional\ItemList\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeListTitleCest
{
    private const LIST_ID = '197c76a8-dcd9-473e-afd8-3ea6556484f3';

    public function it_changes_the_title(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/change-title', ['title' => 'Testing']);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/change-title', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This field is missing.']]);
    }

    public function it_returns_bad_request_when_title_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/change-title', ['title' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_title_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/change-title',[
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value is too long. It should have 50 characters or less.']]);
    }

    public function it_returns_not_found_when_list_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/123456/change-title', ['title' => 'Testing']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
