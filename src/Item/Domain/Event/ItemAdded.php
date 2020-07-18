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
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ItemAdded
 * @package Taranto\ListMaker\Item\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemAdded extends DomainEvent
{
    private const EVENT_TYPE = 'item-added';

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $listId;

    /**
     * ItemAdded constructor.
     * @param string $aggregateId
     * @param string $title
     * @param int $position
     * @param string $listId
     */
    public function __construct(string $aggregateId, string $title, int $position, string $listId)
    {
        parent::__construct($aggregateId);
        $this->title = $title;
        $this->position = $position;
        $this->listId = $listId;
    }

    /**
     * @return ItemId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ItemId::fromString($this->aggregateId);
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return Title::fromString($this->title);
    }

    /**
     * @return Position
     */
    public function position(): Position
    {
        return Position::fromInt($this->position);
    }

    /**
     * @return ListId
     */
    public function listId(): ListId
    {
        return ListId::fromString($this->listId);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}
