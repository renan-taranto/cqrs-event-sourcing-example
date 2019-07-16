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

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class WriteModelDataBase
 * @package Taranto\ListMaker\Tests
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class WriteModelDataBase
{
    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * WriteModelDataBase constructor.
     * @param CommandRunner $commandRunner
     */
    public function __construct(CommandRunner $commandRunner)
    {
        $this->commandRunner = $commandRunner;
    }

    /**
     * @param KernelInterface $kernel
     * @throws \Exception
     */
    public function createDataBase(KernelInterface $kernel): void
    {
        $this->commandRunner->runCommand($kernel, ['command' => 'doctrine:database:create']);
        $this->commandRunner->runCommand($kernel, ['command' => 'event-stream:create']);
    }

    /**
     * @param KernelInterface $kernel
     * @throws \Exception
     */
    public function dropDataBase(KernelInterface $kernel): void
    {
        $this->commandRunner->runCommand($kernel, ['command' => 'doctrine:database:drop', '--if-exists' => true, '--force' => true]);
    }
}
