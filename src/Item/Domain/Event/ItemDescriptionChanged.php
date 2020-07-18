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

use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;

/**
 * Class ItemDescriptionChanged
 * @package Taranto\ListMaker\Item\Domain\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemDescriptionChanged extends DomainEvent
{
    private const EVENT_TYPE = 'item-description-changed';

    /**
     * @var string
     */
    private $description;

    /**
     * ItemDescriptionChanged constructor.
     * @param string $aggregateId
     * @param string $description
     */
    public function __construct(string $aggregateId, string $description)
    {
        parent::__construct($aggregateId);
        $this->description = $description;
    }

    /**
     * @return ItemId
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return ItemId::fromString($this->aggregateId);
    }

    /**
     * @return Description
     */
    public function description(): Description
    {
        return Description::fromString($this->description);
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }
}