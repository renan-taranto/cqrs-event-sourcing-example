<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\SsePublisher;

/**
 * Interface SsePublisher
 * @package Taranto\ListMaker\Shared\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface SsePublisher
{
    /**
     * @param string $url
     * @param string $data
     */
    public function publish(string $url, string $data): void;
}
