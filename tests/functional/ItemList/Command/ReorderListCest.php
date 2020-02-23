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
 * Class ReorderListCest
 * @package Taranto\ListMaker\Tests\Functional\ItemList\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReorderListCest
{
    private const LIST_ID = '197c76a8-dcd9-473e-afd8-3ea6556484f3';

    public function it_reorders_the_list(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/reorder', ['toPosition' => 2]);
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_payload_is_blank(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/reorder', []);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['toPosition' => 'This field is missing.']]);
    }

    public function it_returns_bad_request_when_to_position_is_invalid(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/reorder', ['toPosition' => 3]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['toPosition' => 'Invalid position.']]);
    }

    public function it_returns_bad_request_when_list_is_archived(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/d33a1a8e-5933-4fbc-b60c-0f37d201b2b4/reorder', ['toPosition' => 2]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'Unable to reorder an archived list.']]);
    }

    public function it_returns_not_found_when_list_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/123456/reorder');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
