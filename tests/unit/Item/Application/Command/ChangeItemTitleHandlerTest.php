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
use Taranto\ListMaker\Item\Application\Command\ChangeItemTitle;
use Taranto\ListMaker\Item\Application\Command\ChangeItemTitleHandler;
use Taranto\ListMaker\Item\Domain\Exception\ItemNotFound;
use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Domain\ItemRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ChangeItemTitleHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ChangeItemTitleHandlerTest extends Unit
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ChangeItemTitleHandler
     */
    private $handler;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var ChangeItemTitle
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
        $this->handler = new ChangeItemTitleHandler($this->itemRepository);

        $this->title = Title::fromString('Feature - Update Item');
        $this->itemId = ItemId::generate();
        $this->command = ChangeItemTitle::request(
            (string) $this->itemId,
            ['title' => (string) $this->title]
        );

        $this->item = \Mockery::spy(Item::class);
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_an_item(): void
    {
        $this->itemRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->itemId))
            ->andReturn($this->item);
        $this->itemRepository->shouldReceive('save')->with(isEqual::equalTo($this->item));

        ($this->handler)($this->command);

        $this->item->shouldHaveReceived('changeTitle')->with(isEqual::equalTo($this->title));
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
