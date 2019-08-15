<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Domain\Message;

use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;

/**
 * Class Command
 * @package Taranto\ListMaker\Shared\Domain\Message
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class Command
{
    use MessagePayload;

    /**
     * @var string
     */
    protected $aggregateId;

    /**
     * @param string $aggregateId
     * @param array $payload
     * @return Command
     */
    public static function request(string $aggregateId, array $payload = []): self
    {
        return new static($aggregateId, $payload);
    }

    /**
     * Command constructor.
     * @param string $aggregateId
     * @param array $payload
     */
    protected function __construct(string $aggregateId, array $payload = [])
    {
        $this->aggregateId = $aggregateId;
        $this->payload = $payload;
    }

    /**
     * @return IdentifiesAggregate
     */
    abstract public function aggregateId(): IdentifiesAggregate;
}
