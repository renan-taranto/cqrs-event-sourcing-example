<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Domain\Event;

use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;

/**
 * Class ListRestored
 * @package Taranto\ListMaker\ItemList\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListRestored extends DomainEvent
{
    private const EVENT_TYPE = 'list-restored';

    /**
     * @return ListId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ListId::fromString($this->aggregateId);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}
