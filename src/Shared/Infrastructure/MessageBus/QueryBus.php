<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\MessageBus;

/**
 * Interface QueryBus
 * @package Taranto\ListMaker\Shared\Infrastructure\MessageBus
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface QueryBus
{
    /**
     * @param $message
     * @return mixed
     */
    public function query($message);
}
