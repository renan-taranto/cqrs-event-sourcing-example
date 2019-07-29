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

use MongoDB\Client;

/**
 * Class ReadModelDataBase
 * @package Taranto\ListMaker\Tests
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReadModelDataBase
{
    /**
     * @var string
     */
    private $connectionUrl;

    /**
     * @var string
     */
    private $dataBaseName;

    /**
     * ReadModelDataBase constructor.
     * @param string $connectionUrl
     * @param string $dataBaseName
     */
    public function __construct(string $connectionUrl, string $dataBaseName)
    {
        $this->connectionUrl = $connectionUrl;
        $this->dataBaseName = $dataBaseName;
    }

    public function dropDataBase(): void
    {
        (new Client($this->connectionUrl))->dropDatabase($this->dataBaseName);
    }
}
