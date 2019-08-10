<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Persistence\Projection;

use MongoDB\Client;
use MongoDB\Collection;

/**
 * Class MongoCollectionFactory
 * @package Taranto\ListMaker\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoCollectionFactory
{
    /**
     * @var string
     */
    private $mongoUrl;

    /**
     * @var string
     */
    private $mongoDatabase;

    /**
     * MongoCollectionFactory constructor.
     * @param string $mongoUrl
     * @param string $mongoDatabase
     */
    public function __construct(string $mongoUrl, string $mongoDatabase)
    {
        $this->mongoUrl = $mongoUrl;
        $this->mongoDatabase = $mongoDatabase;
    }

    /**
     * @param string $collectionName
     * @return Collection
     */
    public function createCollection(string $collectionName): Collection
    {
        return (new Client($this->mongoUrl))->{$this->mongoDatabase}->$collectionName;
    }
}
