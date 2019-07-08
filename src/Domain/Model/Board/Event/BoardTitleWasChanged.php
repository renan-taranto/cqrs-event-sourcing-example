<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Board\Event;

use Taranto\ListMaker\Domain\Aggregate\DomainEvent;
use Taranto\ListMaker\Domain\Aggregate\IdentifiesAggregate;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;

/**
 * Class BoardTitleWasChanged
 * @package Taranto\ListMaker\Domain\Model\Board\Event
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardTitleWasChanged extends DomainEvent
{
    private const EVENT_TYPE = 'board-title-was-changed';

    /**
     * @return string
     */
    public function eventType(): string
    {
        return self::EVENT_TYPE;
    }

    /**
     * @return IdentifiesAggregate
     */
    public function aggregateId(): IdentifiesAggregate
    {
        return BoardId::fromString($this->aggregateId);
    }

    /**
     * @return Title
     */
    public function title(): Title
    {
        return Title::fromString($this->payload['title']);
    }
}