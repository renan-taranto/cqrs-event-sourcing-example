<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Domain\Event;

use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class ItemReordered
 * @package Taranto\ListMaker\Item\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemReordered extends DomainEvent
{
    private const EVENT_TYPE = 'item-reordered';

    /**
     * @return ItemId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ItemId::fromString($this->aggregateId);
    }

    /**
     * @return Position
     */
    public function toPosition(): Position
    {
        return Position::fromInt($this->payload['toPosition']);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}