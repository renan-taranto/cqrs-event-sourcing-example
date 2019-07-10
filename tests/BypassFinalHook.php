<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests;

use DG\BypassFinals;
use PHPUnit\Runner\BeforeTestHook;

/**
 * Class BypassFinalHook
 * @package Taranto\ListMaker\Tests
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BypassFinalHook implements BeforeTestHook
{
    public function executeBeforeTest(string $test): void
    {
        BypassFinals::enable();
    }
}
