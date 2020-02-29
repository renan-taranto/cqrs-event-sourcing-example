<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Infrastructure\Persistence\Repository;

use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Domain\ItemRepository as ItemRepositoryInterface;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Repository\AggregateRepository;

/**
 * Class ItemRepository
 * @package Taranto\ListMaker\Item\Infrastructure\Persistence\Repository
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemRepository implements ItemRepositoryInterface
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * ItemRepository constructor.
     * @param AggregateRepository $aggregateRepository
     */
    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    /**
     * @param Item $item
     */
    public function save(Item $item): void
    {
        $this->aggregateRepository->save($item);
    }

    /**
     * @param ItemId $itemId
     * @return Item|null
     */
    public function get(ItemId $itemId): ?Item
    {
        return $this->aggregateRepository->get(Item::class, $itemId);
    }
}
