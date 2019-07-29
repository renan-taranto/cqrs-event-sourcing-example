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

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class IntegrationTestCase
 * @package Taranto\ListMaker\Tests
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class IntegrationTestCase extends KernelTestCase
{
    /**
     * @var WriteModelDataBase
     */
    private $writeModelDataBase;

    /**
     * @var ReadModelDataBase
     */
    private $readModelDataBase;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $this->setUpWriteModelDataBase();
        $this->setUpReadModelDataBase();
    }

    /**
     * @throws \Exception
     */
    private function setUpWriteModelDataBase(): void
    {
        $this->writeModelDataBase = self::$kernel->getContainer()->get('testing.database.write_model');
        $this->writeModelDataBase->dropDataBase(self::$kernel);
        $this->writeModelDataBase->createDataBase(self::$kernel);
    }

    private function setUpReadModelDataBase(): void
    {
        $this->readModelDataBase = self::$kernel->getContainer()->get('testing.database.read_model');
        $this->readModelDataBase->dropDataBase();
    }

    /**
     * @throws \Exception
     */
    protected function tearDown(): void
    {
        $this->tearDownWriteModelDataBase();
    }

    /**
     * @throws \Exception
     */
    private function tearDownWriteModelDataBase(): void
    {
        $this->writeModelDataBase->dropDataBase(self::$kernel);
    }
}
