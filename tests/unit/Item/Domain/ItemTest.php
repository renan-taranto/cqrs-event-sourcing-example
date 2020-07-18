<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Item\Domain;

use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
use Taranto\ListMaker\Item\Domain\Event\ItemReordered;
use Taranto\ListMaker\Item\Domain\Event\ItemRestored;
use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\AggregateRootTestCase;

/**
 * Class ItemTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemTest extends AggregateRootTestCase
{
    /**
     * @var string
     */
    private $itemId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $listId;

    protected function _before()
    {
        $this->itemId = (string) ItemId::generate();
        $this->title = 'Feature - Items';
        $this->position = 0;
        $this->listId = (string) ListId::generate();
    }

    /**
     * @test
     */
    public function it_can_be_added(): void
    {
        $this
            ->when(function () {
                return Item::add(
                    ItemId::fromString($this->itemId),
                    Title::fromString($this->title),
                    Position::fromInt($this->position),
                    ListId::fromString($this->listId)
                );
            })
            ->then([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)]);
    }

    /**
     * @test
     */
    public function description_can_be_changed(): void
    {
        $changedDescription = 'In order to add items...';
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)])
            ->when(function (Item $item) use ($changedDescription) {
                $item->changeDescription(Description::fromString($changedDescription));
            })
            ->then([new ItemDescriptionChanged($this->itemId, $changedDescription)]);
    }

    /**
     * @test
     */
    public function changing_description_records_no_events_when_new_description_equals_old_one(): void
    {
        $description = 'In order to add items...';
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                new ItemAdded($this->itemId, $this->title, $this->position, $this->listId),
                new ItemDescriptionChanged($this->itemId, $description)
            ])
            ->when(function (Item $item) use ($description) {
                $item->changeDescription(Description::fromString($description));
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function title_can_be_changed(): void
    {
        $changedTitle = 'In order to change the title...';
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)])
            ->when(function (Item $item) use ($changedTitle) {
                $item->changeTitle(Title::fromString($changedTitle));
            })
            ->then([new ItemTitleChanged($this->itemId, $changedTitle)]);
    }

    /**
     * @test
     */
    public function changing_title_records_no_events_when_new_title_equals_old_one(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)])
            ->when(function (Item $item) {
                $item->changeTitle(Title::fromString($this->title));
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_archived(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)])
            ->when(function (Item $item) {
                $item->archive();
            })
            ->then([new ItemArchived($this->itemId)]);
    }

    /**
     * @test
     */
    public function archiving_records_no_events_when_already_archived(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                new ItemAdded($this->itemId, $this->title, $this->position, $this->listId),
                new ItemArchived($this->itemId)
            ])
            ->when(function (Item $item) {
                $item->archive();
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_restored(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                new ItemAdded($this->itemId, $this->title, $this->position, $this->listId),
                new ItemArchived($this->itemId)
            ])
            ->when(function (Item $item) {
                $item->restore();
            })
            ->then([new ItemRestored($this->itemId)]);
    }

    /**
     * @test
     */
    public function restoring_records_no_events_when_not_archived(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)])
            ->when(function (Item $item) {
                $item->restore();
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_moved(): void
    {
        $position = Position::fromInt(2);
        $listId = ListId::generate();

        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([new ItemAdded($this->itemId, $this->title, $this->position, $this->listId)])
            ->when(function (Item $item) use ($position, $listId) {
                $item->move($position, $listId);
            })
            ->then([new ItemMoved($this->itemId, $position->toInt(), (string) $listId)]);
    }

    /**
     * @test
     */
    public function moving_records_no_events_when_archived(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                new ItemAdded($this->itemId, $this->title, $this->position, $this->listId),
                new ItemArchived((string) $this->itemId)
            ])
            ->when(function (Item $item) {
                $item->move(Position::fromInt(3), ListId::generate());
            })
            ->then([]);
    }

    protected function getAggregateRootClass(): string
    {
        return Item::class;
    }
}