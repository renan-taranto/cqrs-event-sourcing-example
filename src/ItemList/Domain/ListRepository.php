<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Domain;

/**
 * Interface ListRepository
 * @package Taranto\ListMaker\ItemList\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ListRepository
{
    /**
     * @param ItemList $list
     */
    public function save(ItemList $list): void;

    /**
     * @param ListId $listId
     * @return ItemList|null
     */
    public function get(ListId $listId): ?ItemList;
}
