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

use MongoDB\Collection;
use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListMoved;
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
     * @var Collection
     */
    private $boardsCollection;

    /**
     * ListProjector constructor.
     * @param Collection $boardsCollection
     */
    public function __construct(Collection $boardsCollection)
    {
        $this->boardsCollection = $boardsCollection;
    }

    /**
     * @param ListCreated $event
     */
    protected function projectListCreated(ListCreated $event): void
    {
        $list = [
            'id' => (string) $event->aggregateId(),
            'title' => (string) $event->title(),
            'items' => [],
            'archivedItems' => []
        ];
        $this->boardsCollection->updateOne(
            ['id' => (string) $event->boardId()],
            ['$push' => ['lists' => ['$each' => [$list], '$position' => $event->position()->toInt()]]]
        );
    }

    /**
     * @param ListTitleChanged $event
     */
    protected function projectListTitleChanged(ListTitleChanged $event): void
    {
        $this->boardsCollection->updateOne(
            ['lists.id' => (string) $event->aggregateId()],
            ['$set' => ['lists.$.title' => (string) $event->title()]]
        );
        $this->boardsCollection->updateOne(
            ['archivedLists.id' => (string) $event->aggregateId()],
            ['$set' => ['archivedLists.$.title' => (string) $event->title()]]
        );
    }

    /**
     * @param ListArchived $event
     */
    protected function projectListArchived(ListArchived $event): void
    {
        $list = $this->listById((string) $event->aggregateId());

        $this->boardsCollection->updateOne(
            ['lists' => ['$elemMatch' => $list]],
            [
                '$pull' => ['lists' => ['id' => (string) $event->aggregateId()]],
                '$addToSet' => ['archivedLists' => $list]
            ]
        );
    }

    /**
     * @param ListRestored $event
     */
    protected function projectListRestored(ListRestored $event): void
    {
        $list = $this->archivedListById((string) $event->aggregateId());

        $this->boardsCollection->updateOne(
            ['archivedLists' => ['$elemMatch' => $list]],
            [
                '$pull' => ['archivedLists' => ['id' => (string) $event->aggregateId()]],
                '$addToSet' => ['lists' => $list]
            ]
        );
    }

    /**
     * @param ListMoved $event
     */
    protected function projectListMoved(ListMoved $event): void
    {
        $list = $this->listById((string) $event->aggregateId());

        $this->boardsCollection->updateOne(
            ['lists.id' => (string) $event->aggregateId()],
            ['$pull' => ['lists' => ['id' => (string) $event->aggregateId()]]]
        );

        $this->boardsCollection->updateOne(
            ['id' => (string) $event->boardId()],
            ['$push' => ['lists' => ['$each' => [$list], '$position' => $event->position()->toInt()]]]
        );
    }

    /**
     * @param string $listId
     * @return array
     */
    private function listById(string $listId): array
    {
        return $this->boardsCollection->findOne(
            ['lists.id' => $listId],
            ['projection' => ['lists.$' => true, '_id' => false]]
        )['lists'][0];
    }

    /**
     * @param string $listId
     * @return array
     */
    private function archivedListById(string $listId): array
    {
        return $this->boardsCollection->findOne(
            ['archivedLists.id' => $listId],
            ['projection' => ['archivedLists.$' => true, '_id' => false]]
        )['archivedLists'][0];
    }

}
