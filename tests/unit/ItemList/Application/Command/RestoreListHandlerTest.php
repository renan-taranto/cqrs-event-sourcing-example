<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\ItemList\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\ItemList\Application\Command\RestoreList;
use Taranto\ListMaker\ItemList\Application\Command\RestoreListHandler;
use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository;

/**
 * Class RestoreListHandlerTest
 * @package Taranto\ListMaker\Tests\unit\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class RestoreListHandlerTest extends Unit
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var RestoreListHandler
     */
    private $handler;

    /**
     * @var RestoreList
     */
    private $command;

    /**
     * @var ListId
     */
    private $listId;

    /**
     * @var ItemList
     */
    private $list;

    protected function _before()
    {
        $this->listRepository = \Mockery::mock(ListRepository::class);
        $this->handler = new RestoreListHandler($this->listRepository);

        $this->listId = ListId::generate();
        $this->command = RestoreList::request((string) $this->listId);

        $this->list = \Mockery::spy(ItemList::class);
    }

    /**
     * @test
     */
    public function it_restores_the_list(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn($this->list);
        $this->listRepository->shouldReceive('save')->with(isEqual::equalTo($this->list));

        ($this->handler)($this->command);

        $this->list->shouldHaveReceived('restore');
    }

    /**
     * @test
     */
    public function it_throws_when_list_not_found(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn(null);
        $this->listRepository->shouldReceive('save')->never();

        $this->expectExceptionObject(ListNotFound::withListId($this->listId));

        ($this->handler)($this->command);
    }
}
