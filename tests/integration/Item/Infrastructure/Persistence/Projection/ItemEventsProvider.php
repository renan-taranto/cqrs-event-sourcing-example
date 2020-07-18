<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection;

use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
use Taranto\ListMaker\Item\Domain\Event\ItemRestored;
use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\Item\Domain\ItemId;

/**
 * Trait ItemEventsProvider
 * @package Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
trait ItemEventsProvider
{
    /**
     * @param string $itemId
     * @return ItemAdded
     * @throws \Exception
     */
    private function itemAddedEvent(string $itemId): ItemAdded
    {
        return new ItemAdded((string) ItemId::generate(), 'Feature: Boards Overview', 0, $itemId);
    }

    /**
     * @param string $itemId
     * @return ItemArchived
     */
    private function itemArchivedEvent(string $itemId): ItemArchived
    {
        return new ItemArchived($itemId);
    }

    /**
     * @param string $itemId
     * @return ItemRestored
     */
    private function itemRestoredEvent(string $itemId): ItemRestored
    {
        return new ItemRestored($itemId);
    }

    /**
     * @param string $itemId
     * @return ItemTitleChanged
     */
    private function itemTitleChanged(string $itemId): ItemTitleChanged
    {
        return new ItemTitleChanged($itemId, 'Feature: Snapshots');
    }

    /**
     * @param string $itemId
     * @return ItemDescriptionChanged
     */
    private function itemDescriptionChanged(string $itemId): ItemDescriptionChanged
    {
        return new ItemDescriptionChanged($itemId, 'In order to create snapshots...');
    }

    /**
     * @param string $itemId
     * @param int $position
     * @param string $listId
     * @return ItemMoved
     */
    private function itemMovedEvent(string $itemId, int $position, string $listId): ItemMoved
    {
        return new ItemMoved($itemId, $position, $listId);
    }
}
