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

use MongoDB\Client;
use MongoDB\Collection;

/**
 * Class MongoCollectionProvider
 * @package Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoCollectionProvider
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
    public function getCollection(string $collectionName): Collection
    {
        $driverOptions = ['typeMap' => [
            'array' => 'array',
            'document' => 'array',
            'root' => 'array'
        ]];
        return (new Client($this->mongoUrl, [], $driverOptions))
            ->{$this->mongoDatabase}
            ->$collectionName;
    }
}
