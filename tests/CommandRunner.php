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

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CommandRunner
 * @package Taranto\ListMaker\Tests
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CommandRunner
{
    /**
     * @param KernelInterface $kernel
     * @param array $input
     * @throws \Exception
     */
    public function runCommand(KernelInterface $kernel, array $input): void
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput($input);
        $output = new NullOutput();
        $application->run($input, $output);
    }
}
