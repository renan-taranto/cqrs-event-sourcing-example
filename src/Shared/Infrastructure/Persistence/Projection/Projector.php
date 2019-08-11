<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection;

use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;

/**
 * Class Projector
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class Projector
{
    public function __invoke(DomainEvent $event): void
    {
        $bits = explode("\\", get_class($event));
        $eventClass = end($bits);
        $projectMethod = "project{$eventClass}";

        if (method_exists($this, $projectMethod)) {
            $this->$projectMethod($event);
        }
    }
}
