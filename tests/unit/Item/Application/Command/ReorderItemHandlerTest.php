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
use Taranto\ListMaker\Item\Application\Command\ReorderItem;
use Taranto\ListMaker\Item\Application\Command\ReorderItemHandler;
use Taranto\ListMaker\Item\Domain\Exception\ItemNotFound;
use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Domain\ItemRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class ReorderItemHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReorderItemHandlerTest extends Unit
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ReorderItemHandler
     */
    private $handler;

    /**
     * @var Position
     */
    private $toPosition;

    /**
     * @var ReorderItem
     */
    private $command;

    /**
     * @var ItemId
     */
    private $itemId;

    /**
     * @var Item
     */
    private $item;

    protected function _before()
    {
        $this->itemRepository = \Mockery::spy(ItemRepository::class);
        $this->handler = new ReorderItemHandler($this->itemRepository);

        $this->toPosition = Position::fromInt(2);
        $this->itemId = ItemId::generate();
        $this->command = ReorderItem::request(
            (string) $this->itemId,
            ['toPosition' => $this->toPosition->toInt()]
        );

        $this->item = \Mockery::spy(Item::class);
    }

    /**
     * @test
     */
    public function it_reorders_the_item(): void
    {
        $this->itemRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->itemId))
            ->andReturn($this->item);
        $this->itemRepository->shouldReceive('save')->with(isEqual::equalTo($this->item));

        ($this->handler)($this->command);

        $this->item->shouldHaveReceived('reorder')->with(isEqual::equalTo($this->toPosition));
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
