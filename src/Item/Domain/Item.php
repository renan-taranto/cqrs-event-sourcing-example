<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Domain;

use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\Event\ItemReordered;
use Taranto\ListMaker\Item\Domain\Event\ItemRestored;
use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRoot;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class Item
 * @package Taranto\ListMaker\Item\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class Item extends AggregateRoot
{
    /**
     * @var Title
     */
    private $title;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var bool
     */
    private $archived;

    /**
     * @param ItemId $itemId
     * @param Title $title
     * @param Position $position
     * @param ListId $listId
     * @return Item
     */
    public static function add(ItemId $itemId, Title $title, Position $position, ListId $listId): self
    {
        $instance = new self();

        $instance->recordThat(ItemAdded::occur(
            (string) $itemId,
            ['title' => (string) $title, 'position' => $position->toInt(), 'listId' => (string) $listId]
        ));

        return $instance;
    }

    /**
     * @param ItemAdded $event
     */
    protected function whenItemAdded(ItemAdded $event): void
    {
        $this->aggregateId = $event->aggregateId();
        $this->title = $event->title();
        $this->archived = false;
    }

    /**
     * @param Description $description
     */
    public function changeDescription(Description $description): void
    {
        if ($this->description !== null && $description->equals($this->description)) {
            return;
        }

        $this->recordThat(ItemDescriptionChanged::occur(
            (string) $this->aggregateId,
            ['description' => (string) $description]
        ));
    }

    /**
     * @param ItemDescriptionChanged $event
     */
    protected function whenItemDescriptionChanged(ItemDescriptionChanged $event): void
    {
        $this->description = $event->description();
    }

    /**
     * @param Position $toPosition
     */
    public function reorder(Position $toPosition): void
    {
        if ($this->archived) {
            return;
        }

        $this->recordThat(ItemReordered::occur(
            (string) $this->aggregateId(),
            ['toPosition' => $toPosition->toInt()]
        ));
    }

    /**
     * @param Title $title
     */
    public function changeTitle(Title $title): void
    {
        if ($title->equals($this->title)) {
            return;
        }

        $this->recordThat(ItemTitleChanged::occur(
            (string) $this->aggregateId,
            ['title' => (string) $title]
        ));
    }

    /**
     * @param ItemTitleChanged $event
     */
    protected function whenItemTitleChanged(ItemTitleChanged $event): void
    {
        $this->title = $event->title();
    }

    public function archive(): void
    {
        if ($this->archived) {
            return;
        }

        $this->recordThat(ItemArchived::occur((string) $this->aggregateId));
    }

    protected function whenItemArchived(): void
    {
        $this->archived = true;
    }

    public function restore(): void
    {
        if (!$this->archived) {
            return;
        }

        $this->recordThat(ItemRestored::occur((string) $this->aggregateId));
    }

    protected function whenItemRestored(): void
    {
        $this->archived = false;
    }
}
