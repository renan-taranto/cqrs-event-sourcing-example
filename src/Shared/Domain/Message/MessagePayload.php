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

/**
 * Trait MessagePayload
 * @package Taranto\ListMaker\Shared\Domain\Message
 * @author Renan Taranto <renantaranto@gmail.com>
 */
trait MessagePayload
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
