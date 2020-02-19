<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\functional\ItemList;

use Codeception\Util\HttpCode;
use Taranto\ListMaker\Tests\FunctionalTester;

/**
 * Class RestoreListCest
 * @package Taranto\ListMaker\Tests\functional\ItemList
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class RestoreListCest
{
    private const LIST_ID = 'd33a1a8e-5933-4fbc-b60c-0f37d201b2b4';

    public function it_restore_the_list(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/' . self::LIST_ID . '/restore');
        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
    }

    public function it_returns_bad_request_when_list_is_not_archived(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/197c76a8-dcd9-473e-afd8-3ea6556484f3/restore', ['toPosition' => 3]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['errors' => ['id' => 'The list is not archived.']]);
    }

    public function it_returns_not_found_when_list_not_found(FunctionalTester $I)
    {
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPost('/lists/123456/restore');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
