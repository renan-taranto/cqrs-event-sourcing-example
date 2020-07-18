<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Item\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Item\Application\Command\AddItem;
use Taranto\ListMaker\Item\Application\Command\AddItemHandler;
use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Domain\ItemRepository;
use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class AddItemHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AddItemHandlerTest extends Unit
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var AddItemHandler
     */
    private $handler;

    /**
     * @var AddItem
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

    /**
     * @var Item
     */
    private $item;

    protected function _before()
    {
        $this->itemRepository = \Mockery::spy(ItemRepository::class);
        $this->listRepository = \Mockery::mock(ListRepository::class);
        $this->handler = new AddItemHandler($this->itemRepository, $this->listRepository);

        $itemId = ItemId::generate();
        $title = Title::fromString('Feature - Items');
        $position = Position::fromInt(2);
        $this->listId = ListId::generate();
        $this->command = new AddItem(
            (string) $itemId,
            (string) $title,
            $position->toInt(),
            (string) $this->listId
        );

        $this->list = \Mockery::mock(ItemList::class);
        $this->item = Item::add($itemId, $title, $position, $this->listId);
    }

    /**
     * @test
     */
    public function it_adds_an_item(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn($this->list);

        ($this->handler)($this->command);

        $this->itemRepository->shouldHaveReceived('save')->with(isEqual::equalTo($this->item));
    }

    /**
     * @test
     */
    public function it_throws_when_list_not_found(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturnNull();

        $this->expectExceptionObject(ListNotFound::withListId($this->listId));

        ($this->handler)($this->command);
    }
}
