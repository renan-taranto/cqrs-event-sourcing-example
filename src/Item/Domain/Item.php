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
use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
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

        $instance->recordThat(new ItemAdded(
            (string) $itemId,
            (string) $title,
            $position->toInt(),
            (string) $listId
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

        $this->recordThat(new ItemDescriptionChanged(
            (string) $this->aggregateId,
            (string) $description
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
     * @param Title $title
     */
    public function changeTitle(Title $title): void
    {
        if ($title->equals($this->title)) {
            return;
        }

        $this->recordThat(new ItemTitleChanged(
            (string) $this->aggregateId,
            (string) $title
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

        $this->recordThat(new ItemArchived((string) $this->aggregateId));
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

        $this->recordThat(new ItemRestored((string) $this->aggregateId));
    }

    /**
     * @param Position $position
     * @param ListId $listId
     */
    public function move(Position $position, ListId $listId): void
    {
        if ($this->archived) {
            return;
        }

        $this->recordThat(new ItemMoved(
            (string) $this->aggregateId,
            $position->toInt(),
            (string) $listId
        ));
    }

    protected function whenItemRestored(): void
    {
        $this->archived = false;
    }
}
