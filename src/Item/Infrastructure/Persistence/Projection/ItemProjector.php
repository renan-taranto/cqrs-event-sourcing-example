<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Infrastructure\Persistence\Projection;

use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\Event\ItemReordered;
use Taranto\ListMaker\Item\Domain\Event\ItemRestored;
use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\Projector;

/**
 * Class ItemProjector
 * @package Taranto\ListMaker\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemProjector extends Projector
{
    /**
     * @var ItemProjection
     */
    private $projection;

    /**
     * ItemProjector constructor.
     * @param ItemProjection $projection
     */
    public function __construct(ItemProjection $projection)
    {
        $this->projection = $projection;
    }

    /**
     * @param ItemAdded $event
     */
    protected function projectItemAdded(ItemAdded $event): void
    {
        $this->projection->addItem(
            $event->aggregateId(),
            $event->title(),
            $event->position(),
            $event->listId()
        );
    }

    /**
     * @param ItemTitleChanged $event
     */
    protected function projectItemTitleChanged(ItemTitleChanged $event): void
    {
        $this->projection->changeItemTitle($event->aggregateId(), $event->title());
    }

    /**
     * @param ItemArchived $event
     */
    protected function projectItemArchived(ItemArchived $event): void
    {
        $this->projection->archiveItem($event->aggregateId());
    }

    /**
     * @param ItemRestored $event
     */
    protected function projectItemRestored(ItemRestored $event): void
    {
        $this->projection->restoreItem($event->aggregateId());
    }

    /**
     * @param ItemDescriptionChanged $event
     */
    protected function projectItemDescriptionChanged(ItemDescriptionChanged $event): void
    {
        $this->projection->changeItemDescription($event->aggregateId(), $event->description());
    }

    /**
     * @param ItemReordered $event
     */
    protected function projectItemReordered(ItemReordered $event): void
    {
        $this->projection->reorderItem($event->aggregateId(), $event->toPosition());
    }
}
