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
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class ItemTitleChanged
 * @package Taranto\ListMaker\Item\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemTitleChanged extends DomainEvent
{
    private const EVENT_TYPE = 'item-title-changed';

    /**
     * @var string
     */
    private $title;

    /**
     * ItemTitleChanged constructor.
     * @param string $aggregateId
     * @param string $title
     */
    public function __construct(string $aggregateId, string $title)
    {
        parent::__construct($aggregateId);
        $this->title = $title;
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
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}
