<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Domain;

/**
 * Interface ItemRepository
 * @package Taranto\ListMaker\Item\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ItemRepository
{
    /**
     * @param Item $item
     */
    public function save(Item $item): void;

    /**
     * @param ItemId $itemId
     * @return Item|null
     */
    public function get(ItemId $itemId): ?Item;
}
