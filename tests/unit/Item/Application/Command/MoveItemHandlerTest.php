<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\Item\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Item\Application\Command\MoveItem;
use Taranto\ListMaker\Item\Application\Command\MoveItemHandler;
use Taranto\ListMaker\Item\Domain\Exception\ItemNotFound;
use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Domain\ItemRepository;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class MoveItemHandlerTest
 * @package Taranto\ListMaker\Tests\unit\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MoveItemHandlerTest extends Unit
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var MoveItemHandler
     */
    private $handler;

    /**
     * @var ItemId
     */
    private $itemId;

    /**
     * @var Position
     */
    private $position;

    /**
     * @var ListId
     */
    private $listId;

    /**
     * @var MoveItem
     */
    private $command;

    /**
     * @var Item
     */
    private $item;

    protected function _before()
    {
        $this->itemRepository = \Mockery::spy(ItemRepository::class);
        $this->handler = new MoveItemHandler($this->itemRepository);

        $this->itemId = ItemId::generate();
        $this->position = Position::fromInt(7);
        $this->listId = ListId::generate();
        $this->command = MoveItem::request(
            (string) $this->itemId,
            ['position' => $this->position->toInt(), 'listId' => (string) $this->listId]
        );

        $this->item = \Mockery::spy(Item::class);
    }

    /**
     * @test
     */
    public function it_moves_the_item(): void
    {
        $this->itemRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->itemId))
            ->andReturn($this->item);
        $this->itemRepository->shouldReceive('save')->with(isEqual::equalTo($this->item));

        ($this->handler)($this->command);

        $this->item->shouldHaveReceived('move')->with(
            isEqual::equalTo($this->position),
            isEqual::equalTo($this->listId)
        );
    }

    /**
     * @test
     */
    public function it_throws_when_item_not_found(): void
    {
        $this->itemRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->itemId))
            ->andReturnNull();

        $this->expectExceptionObject(ItemNotFound::withItemId($this->itemId));

        ($this->handler)($this->command);
    }
}
