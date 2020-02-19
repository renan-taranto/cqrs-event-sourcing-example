<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Domain\Exception;

use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRootNotFound;

/**
 * Class ListNotFound
 * @package Taranto\ListMaker\ItemList\Domain\Exception
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListNotFound extends AggregateRootNotFound
{
    /**
     * @param ListId $listId
     * @return static
     */
    public static function withListId(ListId $listId): self
    {
        return new self("List with id {$listId} cannot be found.");
    }
}