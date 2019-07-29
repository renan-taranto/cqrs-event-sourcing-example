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
    public static function createCollection(string $mongoUrl, string $mongoDatabase, string $collection): Collection
    {
        return (new Client($mongoUrl))->$mongoDatabase->$collection;
    }
}
