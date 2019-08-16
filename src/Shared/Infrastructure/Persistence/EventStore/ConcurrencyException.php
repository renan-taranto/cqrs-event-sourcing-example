<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore;

/**
 * Class ConcurrencyException
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\EventStore
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ConcurrencyException extends \RuntimeException
{
}
