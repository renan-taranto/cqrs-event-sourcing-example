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

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Interface ListProjection
 * @package Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ListProjection
{
    /**
     * @param ListId $listId
     * @param Title $listTitle
     * @param BoardId $boardId
     */
    public function createList(ListId $listId, Title $listTitle, BoardId $boardId): void;

    /**
     * @param ListId $listId
     * @param Title $listTitle
     */
    public function changeListTitle(ListId $listId, Title $listTitle): void;

    /**
     * @param ListId $listId
     */
    public function archiveList(ListId $listId): void;

    /**
     * @param ListId $listId
     */
    public function restoreList(ListId $listId): void;

    /**
     * @param ListId $listId
     * @param Position $toPosition
     */
    public function reorderList(ListId $listId, Position $toPosition): void;
}
