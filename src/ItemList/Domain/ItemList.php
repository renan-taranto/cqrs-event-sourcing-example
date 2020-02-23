<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Domain;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListReordered;
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRoot;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ItemList
 * @package Taranto\ListMaker\ItemList\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemList extends AggregateRoot
{
    /**
     * @var Title
     */
    private $title;

    /**
     * @var bool
     */
    private $archived;

    /**
     * @var Position
     */
    private $position;

    /**
     * @param ListId $listId
     * @param Title $title
     * @param Position $position
     * @param BoardId $boardId
     * @return ItemList
     */
    public static function create(ListId $listId, Title $title, Position $position, BoardId $boardId): self
    {
        $instance = new self();

        $instance->recordThat(
            ListCreated::occur(
                (string) $listId,
                ['title' => (string) $title, 'position' => $position->toInt() , 'boardId' => (string) $boardId]
            )
        );

        return $instance;
    }

    /**
     * @param ListCreated $event
     */
    protected function whenListCreated(ListCreated $event): void
    {
        $this->aggregateId = $event->aggregateId();
        $this->title = $event->title();
        $this->position = $event->position();
        $this->archived = false;
    }

    /**
     * @param Title $title
     */
    public function changeTitle(Title $title): void
    {
        if ($title->equals($this->title)) {
            return;
        }

        $this->recordThat(ListTitleChanged::occur((string) $this->aggregateId, ['title' => (string) $title]));
    }

    /**
     * @param ListTitleChanged $event
     */
    protected function whenListTitleChanged(ListTitleChanged $event): void
    {
        $this->title = $event->title();
    }

    public function archive(): void
    {
        if ($this->archived) {
            return;
        }

        $this->recordThat(ListArchived::occur((string) $this->aggregateId));
    }

    protected function whenListArchived(): void
    {
        $this->archived = true;
    }

    public function restore(): void
    {
        if (!$this->archived) {
            return;
        }

        $this->recordThat(ListRestored::occur((string) $this->aggregateId));
    }

    protected function whenListRestored(): void
    {
        $this->archived = false;
    }

    /**
     * @param Position $toPosition
     */
    public function reorder(Position $toPosition): void
    {
        if (
            ($this->position !== null && $this->position->equals($toPosition))
            || $this->archived
        ) {
            return;
        }

        $this->recordThat(ListReordered::occur(
            (string) $this->aggregateId,
            ['toPosition' => $toPosition->toInt()]
        ));
    }

    /**
     * @param ListReordered $event
     */
    protected function whenListReordered(ListReordered $event): void
    {
        $this->position = $event->toPosition();
    }
}
