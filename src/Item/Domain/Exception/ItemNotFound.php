<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Domain\Exception;

use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRootNotFound;

/**
 * Class ItemNotFound
 * @package Taranto\ListMaker\Item\Domain\Exception
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemNotFound extends AggregateRootNotFound
{
    /**
     * @param ItemId $itemId
     * @return static
     */
    public static function withItemId(ItemId $itemId): self
    {
        return new self("Item with id {$itemId} cannot be found.");
    }
}
