<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Extension;

use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Extension;

/**
 * Class ByPassFinals
 * @package Taranto\ListMaker\Tests\Helper\Extension
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ByPassFinals extends Extension
{
    public static $events = [
        Events::TEST_START => 'onTestStart'
    ];

    /**
     * @param TestEvent $e
     */
    public function onTestStart(TestEvent $e)
    {
        \DG\BypassFinals::enable();
    }
}
