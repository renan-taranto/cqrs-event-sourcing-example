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
 * Class ChangeItemDescriptionCest
 * @package Taranto\ListMaker\Tests\Functional\Item\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeItemDescriptionCest
{
    private const ITEM_ID = 'c8f94b93-a41d-490d-85e0-47990bc4792f';

    public function it_changes_the_description_of_an_item(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/change-description', ['description' => 'As an API user...']);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_description_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/change-description', ['description' => '']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['description' => 'This value should not be blank.']]);
    }

    public function it_returns_bad_request_when_description_length_is_greater_than_limit(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/' . self::ITEM_ID . '/change-description',[
            'description' => bin2hex(random_bytes(16000))
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['description' => 'This value is too long. It should have 16000 characters or less.']]);
    }

    public function it_returns_not_found_when_item_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/items/123456/change-description', ['description' => 'As an API user...']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}