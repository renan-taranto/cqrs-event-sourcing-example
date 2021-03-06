<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Domain\Message;

/**
 * Class DomainEvents
 * @package Taranto\ListMaker\Shared\Domain\Message
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class DomainEvents extends ImmutableArray
{
    /**
     * @throws \InvalidArgumentException Throw when the item is not an instance of the accepted type.
     * @param $item
     */
    protected function guardType($item): void
    {
        if (!$item instanceof DomainEvent) {
            throw new \InvalidArgumentException('DomainEvents items must be instances of the DomainEvent class');
        }
    }
}
