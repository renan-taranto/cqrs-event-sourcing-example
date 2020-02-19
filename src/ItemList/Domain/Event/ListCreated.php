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

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ListCreated
 * @package Taranto\ListMaker\ItemList\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListCreated extends DomainEvent
{
    private const EVENT_TYPE = 'list-created';

    /**
     * @return ListId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ListId::fromString($this->aggregateId);
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return Title::fromString($this->payload['title']);
    }

    /**
     * @return BoardId
     */
    public function boardId(): BoardId
    {
        return BoardId::fromString($this->payload['boardId']);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}
