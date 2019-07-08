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
 * Class DomainMessage
 * @package Taranto\ListMaker\Domain\Model\Common
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class DomainMessage
{
    /**
     * @var string
     */
    protected $aggregateId;

    /**
     * @var array
     */
    protected $payload;

    protected function __construct(string $aggregateId, array $payload = [])
    {
        $this->aggregateId = $aggregateId;
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }

    /**
     * @return IdentifiesAggregate
     */
    abstract public function aggregateId(): IdentifiesAggregate;
}
