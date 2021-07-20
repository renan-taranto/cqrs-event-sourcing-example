<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Taranto\ListMaker\Item\Infrastructure\Persistence\Projection\ItemFinder;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class ItemFinderTest
 * @package Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemFinderTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var ItemFinder
     */
    private $itemFinder;

    protected function _before(): void
    {
        $this->itemFinder = $this->tester->grabService('test.service_container')->get(ItemFinder::class);
    }

    /**
     * @test
     */
    public function it_returns_an_item_with_the_given_id(): void
    {
        $itemId = 'fbac36d6-fbbc-4013-bed3-2a0fdfd92956';
        $item = $this->itemFinder->byId($itemId);

        expect($item['id'])->equals($itemId);
    }
}
