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
        return ItemAdded::occur(
            (string) ItemId::generate(),
            ['title' => 'Feature: Boards Overview', 'position' => 0, 'listId' => $itemId]
        );
    }

    /**
     * @param string $itemId
     * @return ItemArchived
     */
    private function itemArchivedEvent(string $itemId): ItemArchived
    {
        return ItemArchived::occur($itemId);
    }

    /**
     * @param string $itemId
     * @return ItemRestored
     */
    private function itemRestoredEvent(string $itemId): ItemRestored
    {
        return ItemRestored::occur($itemId);
    }

    /**
     * @param string $itemId
     * @return ItemTitleChanged
     */
    private function itemTitleChanged(string $itemId): ItemTitleChanged
    {
        return ItemTitleChanged::occur(
            $itemId,
            ['title' => 'Feature: Snapshots']
        );
    }

    /**
     * @param string $itemId
     * @return ItemDescriptionChanged
     */
    private function itemDescriptionChanged(string $itemId): ItemDescriptionChanged
    {
        return ItemDescriptionChanged::occur(
            $itemId,
            ['description' => 'In order to create snapshots...']
        );
    }

    /**
     * @param string $itemId
     * @param int $position
     * @param string $listId
     * @return ItemMoved
     */
    private function itemMovedEvent(string $itemId, int $position, string $listId): ItemMoved
    {
        return ItemMoved::occur(
            $itemId,
            ['position' => $position, 'listId' => $listId]
        );
    }
}
