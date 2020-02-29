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
 * Class ChangeItemTitleCest
 * @package Taranto\ListMaker\Tests\Functional\Item\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeItemTitleCest
{
    private const ITEM_ID = 'c8f94b93-a41d-490d-85e0-47990bc4792f';

    public function it_changes_the_title_of_an_item(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/change-title', ['title' => 'Feature: Change Item Title']);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_title_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/change-title', ['title' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_title_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/change-title',[
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['title' => 'This value is too long. It should have 50 characters or less.']]);
    }

    public function it_returns_not_found_when_item_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/123456/change-title', ['title' => 'Feature: Change Item Title']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
