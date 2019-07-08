<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Common;

/**
 * Class DomainEvent
 * @package Taranto\ListMaker\Domain\Model\Common
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class DomainEvent extends DomainMessage
{
    /**
     * @param string $aggregateId
     * @param array $payload
     * @return DomainEvent
     */
    public static function occur(string $aggregateId, array $payload = []): self
    {
        return new static($aggregateId, $payload);
    }

    /**
     * @return string
     */
    abstract public function eventType(): string;
}
