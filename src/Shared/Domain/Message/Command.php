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
    /**
     * @var string
     */
    protected $aggregateId;

    /**
     * Command constructor.
     * @param string $aggregateId
     */
    public function __construct(string $aggregateId)
    {
        $this->aggregateId = $aggregateId;
    }

    /**
     * @return IdentifiesAggregate
     */
    abstract public function aggregateId(): IdentifiesAggregate;
}
