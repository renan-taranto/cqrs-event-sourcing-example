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
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class ListMoved
 * @package Taranto\ListMaker\ItemList\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListMoved extends DomainEvent
{
    private const EVENT_TYPE = 'list-moved';

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $boardId;

    /**
     * ListMoved constructor.
     * @param string $aggregateId
     * @param int $position
     * @param string $boardId
     */
    public function __construct(string $aggregateId, int $position, string $boardId)
    {
        parent::__construct($aggregateId);
        $this->position = $position;
        $this->boardId = $boardId;
    }

    /**
     * @return ListId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ListId::fromString($this->aggregateId);
    }

    /**
     * @return Position
     */
    public function position(): Position
    {
        return Position::fromInt($this->position);
    }

    /**
     * @return BoardId
     */
    public function boardId(): BoardId
    {
        return BoardId::fromString($this->boardId);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}
