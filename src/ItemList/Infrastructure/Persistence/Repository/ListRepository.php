<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Infrastructure\Persistence\Repository;

use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository as ListRepositoryInterface;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Repository\AggregateRepository;

/**
 * Class ListRepository
 * @package Taranto\ListMaker\ItemList\Infrastructure\Persistence\Repository
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListRepository implements ListRepositoryInterface
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * ListRepository constructor.
     * @param AggregateRepository $aggregateRepository
     */
    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    /**
     * @param ItemList $list
     */
    public function save(ItemList $list): void
    {
        $this->aggregateRepository->save($list);
    }

    /**
     * @param ListId $listId
     * @return ItemList|null
     */
    public function get(ListId $listId): ?ItemList
    {
        return $this->aggregateRepository->get(ItemList::class, $listId);
    }
}
