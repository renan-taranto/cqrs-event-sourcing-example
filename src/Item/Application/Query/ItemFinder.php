<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Application\Query;

/**
 * Interface ItemFinder
 * @package Taranto\ListMaker\Item\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ItemFinder
{
    /**
     * @param string $itemId
     * @return array|null
     */
    public function byId(string $itemId): ?array;
}
