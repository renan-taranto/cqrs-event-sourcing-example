<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Application\Query;

/**
 * Interface ListFinder
 * @package Taranto\ListMaker\ItemList\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ListFinder
{
    /**
     * @param string $listId
     * @return array|null
     */
    public function byId(string $listId): ?array;
}
