<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Infrastructure\Persistence\Projection;

use MongoDB\Collection;
use Taranto\ListMaker\Item\Application\Query\ItemFinder as ItemFinderInterface;

/**
 * Class ItemFinder
 * @package Taranto\ListMaker\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemFinder implements ItemFinderInterface
{
    /**
     * @var Collection
     */
    private $boardsCollection;

    /**
     * ItemFinder constructor.
     * @param Collection $boardsCollection
     */
    public function __construct(Collection $boardsCollection)
    {
        $this->boardsCollection = $boardsCollection;
    }

    /**
     * @param string $itemId
     * @return array|null
     */
    public function byId(string $itemId): ?array
    {
        return $this->boardsCollection->aggregate(
            [
                ['$unwind' => '$lists'],
                ['$unwind' => '$lists.items'],
                ['$match' => ['lists.items.id' => $itemId]],
                ['$project' => ['lists.items' => true, '_id' => false]]
            ]
        )->toArray()[0]['lists']['items'];
    }
}
