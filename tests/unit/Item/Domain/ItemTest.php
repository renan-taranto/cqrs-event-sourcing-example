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
            ->then([
                ItemAdded::occur(
                    $this->itemId,
                    ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
                )
            ]);
    }

    /**
     * @test
     */
    public function description_can_be_changed(): void
    {
        $changedDescription = 'In order to add items...';
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([ItemAdded::occur(
                $this->itemId,
                ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
            )])
            ->when(function (Item $item) use ($changedDescription) {
                $item->changeDescription(Description::fromString($changedDescription));
            })
            ->then([ItemDescriptionChanged::occur($this->itemId, ['description' => $changedDescription])]);
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
                ItemAdded::occur(
                    $this->itemId,
                    ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
                ),
                ItemDescriptionChanged::occur($this->itemId, ['description' => $description])
            ])
            ->when(function (Item $item) use ($description) {
                $item->changeDescription(Description::fromString($description));
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_reordered(): void
    {
        $toPosition = 2;
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([ItemAdded::occur(
                $this->itemId,
                ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
            )])
            ->when(function (Item $item) use ($toPosition) {
                $item->reorder(Position::fromInt($toPosition));
            })
            ->then([ItemReordered::occur($this->itemId, ['toPosition' => $toPosition])]);
    }

    /**
     * @test
     */
    public function reordering_records_no_events_when_new_position_equals_old_one(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([ItemAdded::occur(
                $this->itemId,
                ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
            )])
            ->when(function (Item $item) {
                $item->reorder(Position::fromInt($this->position));
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
            ->given([ItemAdded::occur(
                $this->itemId,
                ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
            )])
            ->when(function (Item $item) use ($changedTitle) {
                $item->changeTitle(Title::fromString($changedTitle));
            })
            ->then([ItemTitleChanged::occur($this->itemId, ['title' => $changedTitle])]);
    }

    /**
     * @test
     */
    public function changing_title_records_no_events_when_new_title_equals_old_one(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                ItemAdded::occur(
                    $this->itemId,
                    ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
                )
            ])
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
            ->given([ItemAdded::occur(
                $this->itemId,
                ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
            )])
            ->when(function (Item $item) {
                $item->archive();
            })
            ->then([ItemArchived::occur($this->itemId)]);
    }

    /**
     * @test
     */
    public function archiving_records_no_events_when_already_archived(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                ItemAdded::occur(
                    $this->itemId,
                    ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
                ),
                ItemArchived::occur($this->itemId)
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
                ItemAdded::occur(
                    $this->itemId,
                    ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
                ),
                ItemArchived::occur($this->itemId)
            ])
            ->when(function (Item $item) {
                $item->restore();
            })
            ->then([ItemRestored::occur($this->itemId)]);
    }

    /**
     * @test
     */
    public function restoring_records_no_events_when_not_archived(): void
    {
        $this
            ->withAggregateId(ItemId::fromString($this->itemId))
            ->given([
                ItemAdded::occur(
                    $this->itemId,
                    ['title' => $this->title, 'position' => $this->position, 'listId' => $this->listId]
                )
            ])
            ->when(function (Item $item) {
                $item->restore();
            })
            ->then([]);
    }

    protected function getAggregateRootClass(): string
    {
        return Item::class;
    }
}