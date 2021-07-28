<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection;

use MongoDB\Collection;
use Taranto\ListMaker\ItemList\Application\Query\ListFinder as ListFinderInterface;

/**
 * Class ListFinder
 * @package Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListFinder implements ListFinderInterface
{
    /**
     * @var Collection
     */
    private $boardsCollection;

    /**
     * ListFinder constructor.
     * @param Collection $boardsCollection
     */
    public function __construct(Collection $boardsCollection)
    {
        $this->boardsCollection = $boardsCollection;
    }

    /**
     * @param string $listId
     * @return string[]|null
     */
    public function byId(string $listId): ?array
    {
        return $this->boardsCollection->findOne(
            ['lists.id' => $listId],
            ['projection' => ['lists.$' => true, '_id' => false]]
        )['lists'][0];
    }
}
