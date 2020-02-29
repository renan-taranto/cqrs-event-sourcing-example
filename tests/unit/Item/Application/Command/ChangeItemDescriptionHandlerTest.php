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
use Taranto\ListMaker\Item\Application\Command\ChangeItemDescription;
use Taranto\ListMaker\Item\Application\Command\ChangeItemDescriptionHandler;
use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\Exception\ItemNotFound;
use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Domain\ItemRepository;

/**
 * Class ChangeItemDescriptionHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeItemDescriptionHandlerTest extends Unit
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ChangeItemDescriptionHandler
     */
    private $handler;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var ChangeItemDescription
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
        $this->handler = new ChangeItemDescriptionHandler($this->itemRepository);

        $this->description = Description::fromString('An updated item description...');
        $this->itemId = ItemId::generate();
        $this->command = ChangeItemDescription::request(
            (string) $this->itemId,
            ['description' => (string) $this->description]
        );

        $this->item = \Mockery::spy(Item::class);
    }

    /**
     * @test
     */
    public function it_changes_the_description_of_an_item(): void
    {
        $this->itemRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->itemId))
            ->andReturn($this->item);
        $this->itemRepository->shouldReceive('save')->with(isEqual::equalTo($this->item));

        ($this->handler)($this->command);

        $this->item->shouldHaveReceived('changeDescription')->with(isEqual::equalTo($this->description));
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
