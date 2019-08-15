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
 * Class Query
 * @package Taranto\ListMaker\Shared\Domain\Message
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class Query
{
    use MessagePayload;

    /**
     * Query constructor.
     * @param array $payload
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }
}
