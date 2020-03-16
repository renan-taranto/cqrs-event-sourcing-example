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

use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ItemProjection
 * @package Taranto\ListMaker\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ItemProjection
{
    /**
     * @param ItemId $itemId
     * @param Title $title
     * @param Position $position
     * @param ListId $listId
     */
    public function addItem(ItemId $itemId, Title $title, Position $position, ListId $listId): void;

    /**
     * @param ItemId $itemId
     * @param Title $title
     */
    public function changeItemTitle(ItemId $itemId, Title $title): void;

    /**
     * @param ItemId $itemId
     */
    public function archiveItem(ItemId $itemId): void;

    /**
     * @param ItemId $itemId
     */
    public function restoreItem(ItemId $itemId): void;

    /**
     * @param ItemId $itemId
     * @param Description $description
     * @return mixed
     */
    public function changeItemDescription(ItemId $itemId, Description $description): void;

    /**
     * @param ItemId $itemId
     * @param Position $toPosition
     */
    public function reorderItem(ItemId $itemId, Position $toPosition): void;

    /**
     * @param ItemId $itemId
     * @param Position $position
     * @param ListId $listId
     */
    public function moveItem(ItemId $itemId, Position $position, ListId $listId): void;
}
