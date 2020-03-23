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

use MongoDB\Collection;
use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
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
     * @var Collection
     */
    private $boardsCollection;

    /**
     * ItemProjector constructor.
     * @param Collection $boardsCollection
     */
    public function __construct(Collection $boardsCollection)
    {
        $this->boardsCollection = $boardsCollection;
    }

    /**
     * @param ItemAdded $event
     */
    protected function projectItemAdded(ItemAdded $event): void
    {
        $item = [
            'id' => (string) $event->aggregateId(),
            'title' => (string) $event->title(),
            'description' => ''
        ];
        $this->boardsCollection->updateOne(
            ['lists.id' => (string) $event->listId()],
            ['$push' => ['lists.$.items' => ['$each' => [$item], '$position' => $event->position()->toInt()]]]
        );
    }

    /**
     * @param ItemTitleChanged $event
     */
    protected function projectItemTitleChanged(ItemTitleChanged $event): void
    {
        $this->boardsCollection->updateOne(
            ['lists.items.id' => (string) $event->aggregateId()],
            ['$set' => ['lists.$[].items.$[i].title' => (string) $event->title()]],
            ['arrayFilters' => [['i.id' => (string) $event->aggregateId()]]]
        );
    }

    /**
     * @param ItemArchived $event
     */
    protected function projectItemArchived(ItemArchived $event): void
    {
        $item = $this->itemById((string) $event->aggregateId());

        $this->boardsCollection->updateOne(
            ['lists.items' => ['$elemMatch' => $item]],
            [
                '$pull' => ['lists.$.items' => ['id' => (string) $event->aggregateId()]],
                '$addToSet' => ['lists.$.archivedItems' => $item]
            ]
        );
    }

    /**
     * @param ItemRestored $event
     */
    protected function projectItemRestored(ItemRestored $event): void
    {
        $item = $this->archivedItemById((string) $event->aggregateId());

        $this->boardsCollection->updateOne(
            ['lists.archivedItems' => ['$elemMatch' => $item]],
            [
                '$pull' => ['lists.$.archivedItems' => ['id' => (string) $event->aggregateId()]],
                '$addToSet' => ['lists.$.items' => $item]
            ]
        );
    }

    /**
     * @param ItemDescriptionChanged $event
     */
    protected function projectItemDescriptionChanged(ItemDescriptionChanged $event): void
    {
        $this->boardsCollection->updateOne(
            ['lists.items.id' => (string) $event->aggregateId()],
            ['$set' => ['lists.$[].items.$[i].description' => (string) $event->description()]],
            ['arrayFilters' => [['i.id' => (string) $event->aggregateId()]]]
        );
    }

    /**
     * @param ItemMoved $event
     */
    protected function projectItemMoved(ItemMoved $event): void
    {
        $item = $this->itemById((string) $event->aggregateId());

        $this->boardsCollection->updateOne(
            ['lists.items' => ['$elemMatch' => $item]],
            ['$pull' => ['lists.$.items' => ['id' => (string) $event->aggregateId()]]]
        );

        $this->boardsCollection->updateOne(
            ['lists.id' => (string) $event->listId()],
            ['$push' => ['lists.$.items' => ['$each' => [$item], '$position' => $event->position()->toInt()]]]
        );
    }

    /**
     * @param string $id
     * @return array
     */
    private function itemById(string $id): array
    {
        return $this->boardsCollection->aggregate(
            [
                ['$unwind' => '$lists'],
                ['$unwind' => '$lists.items'],
                ['$match' => ['lists.items.id' => $id]],
                ['$project' => ['lists.items' => true, '_id' => false]]
            ]
        )->toArray()[0]['lists']['items'];
    }

    /**
     * @param string $id
     * @return array
     */
    private function archivedItemById(string $id): array
    {
        return $this->boardsCollection->aggregate(
            [
                ['$unwind' => '$lists'],
                ['$unwind' => '$lists.archivedItems'],
                ['$match' => ['lists.archivedItems.id' => $id]],
                ['$project' => ['lists.archivedItems' => true, '_id' => false]]
            ]
        )->toArray()[0]['lists']['archivedItems'];
    }
}
