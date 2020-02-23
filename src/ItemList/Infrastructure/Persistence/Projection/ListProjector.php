<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection;

use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListReordered;
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\Projector;

/**
 * Class ListProjector
 * @package Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListProjector extends Projector
{
    /**
     * @var ListProjection
     */
    private $projection;

    /**
     * ListProjector constructor.
     * @param ListProjection $projection
     */
    public function __construct(ListProjection $projection)
    {
        $this->projection = $projection;
    }

    /**
     * @param ListCreated $event
     */
    protected function projectListCreated(ListCreated $event): void
    {
        $this->projection->createList($event->aggregateId(), $event->title(), $event->position(), $event->boardId());
    }

    /**
     * @param ListTitleChanged $event
     */
    protected function projectListTitleChanged(ListTitleChanged $event): void
    {
        $this->projection->changeListTitle($event->aggregateId(), $event->title());
    }

    /**
     * @param ListArchived $event
     */
    protected function projectListArchived(ListArchived $event): void
    {
        $this->projection->archiveList($event->aggregateId());
    }

    /**
     * @param ListRestored $event
     */
    protected function projectListRestored(ListRestored $event): void
    {
        $this->projection->restoreList($event->aggregateId());
    }

    /**
     * @param ListReordered $event
     */
    protected function projectListReordered(ListReordered $event): void
    {
        $this->projection->reorderList($event->aggregateId(), $event->toPosition());
    }
}
