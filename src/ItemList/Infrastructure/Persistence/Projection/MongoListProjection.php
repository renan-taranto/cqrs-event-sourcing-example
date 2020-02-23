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
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class MongoListProjection
 * @package Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoListProjection implements ListProjection
{
    /**
     * @var Collection
     */
    private $boardCollection;

    /**
     * MongoListProjection constructor.
     * @param Collection $boardCollection
     */
    public function __construct(Collection $boardCollection)
    {
        $this->boardCollection = $boardCollection;
    }

    /**
     * @param ListId $listId
     * @param Title $listTitle
     * @param BoardId $boardId
     */
    public function createList(ListId $listId, Title $listTitle, BoardId $boardId): void
    {
        $this->boardCollection->updateOne(
            ['id' => (string) $boardId],
            ['$push' => [
                'lists' => ['id' => (string) $listId, 'title' => (string) $listTitle, 'items' => []]
            ]]
        );
    }

    /**
     * @param ListId $listId
     * @param Title $listTitle
     */
    public function changeListTitle(ListId $listId, Title $listTitle): void
    {
        $this->boardCollection->updateOne(
            ['lists.id' => (string) $listId],
            ['$set' => ['lists.$.title' => (string) $listTitle]]
        );
        $this->boardCollection->updateOne(
            ['archivedLists.id' => (string) $listId],
            ['$set' => ['archivedLists.$.title' => (string) $listTitle]]
        );
    }

    /**
     * @param ListId $listId
     */
    public function archiveList(ListId $listId): void
    {
        $list = $this->boardCollection->findOne(
            ['lists.id' => (string) $listId],
            ['projection' => ['lists.$' => true, '_id' => false]]
        )['lists'][0];

        $this->boardCollection->updateOne(
            ['lists' => ['$elemMatch' => $list]],
            [
                '$pull' => ['lists' => ['id' => (string) $listId]],
                '$addToSet' => ['archivedLists' => $list]
            ]
        );
    }

    /**
     * @param ListId $listId
     */
    public function restoreList(ListId $listId): void
    {
        $list = $this->boardCollection->findOne(
            ['archivedLists.id' => (string) $listId],
            ['projection' => ['archivedLists.$' => true, '_id' => false]]
        )['archivedLists'][0];

        $this->boardCollection->updateOne(
            ['archivedLists' => ['$elemMatch' => $list]],
            [
                '$pull' => ['archivedLists' => ['id' => (string) $listId]],
                '$addToSet' => ['lists' => $list]
            ]
        );
    }

    /**
     * @param ListId $listId
     * @param Position $toPosition
     */
    public function reorderList(ListId $listId, Position $toPosition): void
    {
        $boardId = $this->boardCollection->findOne(
            ['lists.id' => (string) $listId],
            ['projection' => ['_id' => false, 'id' => true]]
        )['id'];

        $list = $this->boardCollection->findOne(
            ['lists.id' => (string) $listId],
            ['projection' => ['lists.$' => true, '_id' => false]]
        )['lists'][0];

        $this->boardCollection->updateOne(
            ['lists.id' => (string) $listId],
            ['$pull' => ['lists' => ['id' => (string) $listId]]]
        );

        $this->boardCollection->updateOne(
            ['id' => $boardId],
            ['$push' => ['lists' => ['$each' => [$list], '$position' => $toPosition->toInt()]]]
        );
    }
}
