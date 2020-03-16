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
use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class MongoItemProjection
 * @package Taranto\ListMaker\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoItemProjection implements ItemProjection
{
    /**
     * @var Collection
     */
    private $boardCollection;

    /**
     * MongoItemProjection constructor.
     * @param Collection $boardCollection
     */
    public function __construct(Collection $boardCollection)
    {
        $this->boardCollection = $boardCollection;
    }

    /**
     * @param ItemId $itemId
     * @param Title $title
     * @param Position $position
     * @param ListId $listId
     */
    public function addItem(ItemId $itemId, Title $title, Position $position, ListId $listId): void
    {
        $item = ['id' => (string) $itemId, 'title' => (string) $title, 'description' => ''];
        $this->boardCollection->updateOne(
            ['lists.id' => (string) $listId],
            ['$push' => ['lists.$.items' => ['$each' => [$item], '$position' => $position->toInt()]]]
        );
    }

    /**
     * @param ItemId $itemId
     * @param Title $title
     */
    public function changeItemTitle(ItemId $itemId, Title $title): void
    {
        $this->boardCollection->updateOne(
            ['lists.items.id' => (string) $itemId],
            ['$set' => ['lists.$[].items.$[i].title' => (string) $title]],
            ['arrayFilters' => [['i.id' => (string) $itemId]]]
        );
    }

    /**
     * @param ItemId $itemId
     */
    public function archiveItem(ItemId $itemId): void
    {
        $item = $this->itemById((string) $itemId);

        $this->boardCollection->updateOne(
            ['lists.items' => ['$elemMatch' => $item]],
            [
                '$pull' => ['lists.$.items' => ['id' => (string) $itemId]],
                '$addToSet' => ['lists.$.archivedItems' => $item]
            ]
        );
    }

    /**
     * @param ItemId $itemId
     */
    public function restoreItem(ItemId $itemId): void
    {
        $item = $this->archivedItemById((string) $itemId);

        $this->boardCollection->updateOne(
            ['lists.archivedItems' => ['$elemMatch' => $item]],
            [
                '$pull' => ['lists.$.archivedItems' => ['id' => (string) $itemId]],
                '$addToSet' => ['lists.$.items' => $item]
            ]
        );
    }

    /**
     * @param ItemId $itemId
     * @param Description $description
     */
    public function changeItemDescription(ItemId $itemId, Description $description): void
    {
        $this->boardCollection->updateOne(
            ['lists.items.id' => (string) $itemId],
            ['$set' => ['lists.$[].items.$[i].description' => (string) $description]],
            ['arrayFilters' => [['i.id' => (string) $itemId]]]
        );
    }

    /**
     * @param ItemId $itemId
     * @param Position $toPosition
     */
    public function reorderItem(ItemId $itemId, Position $toPosition): void
    {
        $listId = $this->listIdByItemId((string) $itemId);
        $item = $this->itemById((string) $itemId);

        $this->boardCollection->updateOne(
            ['lists.items' => ['$elemMatch' => $item]],
            ['$pull' => ['lists.$.items' => ['id' => (string) $itemId]]]
        );

        $this->boardCollection->updateOne(
            ['lists.id' => $listId],
            ['$push' => ['lists.$.items' => ['$each' => [$item], '$position' => $toPosition->toInt()]]]
        );
    }

    /**
     * @param ItemId $itemId
     * @param Position $position
     * @param ListId $listId
     */
    public function moveItem(ItemId $itemId, Position $position, ListId $listId): void
    {
        $item = $this->itemById((string) $itemId);

        $this->boardCollection->updateOne(
            ['lists.items' => ['$elemMatch' => $item]],
            ['$pull' => ['lists.$.items' => ['id' => (string) $itemId]]]
        );

        $this->boardCollection->updateOne(
            ['lists.id' => (string) $listId],
            ['$push' => ['lists.$.items' => ['$each' => [$item], '$position' => $position->toInt()]]]
        );
    }

    /**
     * @param string $id
     * @return array
     */
    private function itemById(string $id): array
    {
        return $this->boardCollection->aggregate(
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
        return $this->boardCollection->aggregate(
            [
                ['$unwind' => '$lists'],
                ['$unwind' => '$lists.archivedItems'],
                ['$match' => ['lists.archivedItems.id' => $id]],
                ['$project' => ['lists.archivedItems' => true, '_id' => false]]
            ]
        )->toArray()[0]['lists']['archivedItems'];
    }

    /**
     * @param string $itemId
     * @return string
     */
    private function listIdByItemId(string $itemId): string
    {
        return $this->boardCollection->aggregate([
            ['$unwind' => '$lists'],
            ['$match' => ['lists.items.id' => (string) $itemId]],
            ['$project' => ['lists.id' => true, '_id' => false]]
        ])->toArray()[0]['lists']['id'];
    }
}
