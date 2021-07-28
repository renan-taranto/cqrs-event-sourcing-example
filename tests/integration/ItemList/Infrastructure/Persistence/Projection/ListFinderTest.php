<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\ItemList\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection\ListFinder;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class ListFinderTest
 * @package Taranto\ListMaker\Tests\Integration\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListFinderTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var ListFinder
     */
    private $listFinder;

    protected function _before(): void
    {
        $this->listFinder = $this->tester->grabService('test.service_container')->get(ListFinder::class);
    }

    /**
     * @test
     */
    public function it_returns_a_list_with_the_given_id(): void
    {
        $listId = '197c76a8-dcd9-473e-afd8-3ea6556484f3';
        $list = $this->listFinder->byId($listId);

        expect($list['id'])->equals($listId);
    }
}
