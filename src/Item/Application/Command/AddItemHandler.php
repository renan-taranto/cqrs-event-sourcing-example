<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Application\Command;

use Taranto\ListMaker\Item\Domain\Item;
use Taranto\ListMaker\Item\Domain\ItemRepository;
use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ListRepository;

/**
 * Class AddItemHandler
 * @package Taranto\ListMaker\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class AddItemHandler
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * AddItemHandler constructor.
     * @param ItemRepository $itemRepository
     * @param ListRepository $listRepository
     */
    public function __construct(ItemRepository $itemRepository, ListRepository $listRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->listRepository = $listRepository;
    }

    /**
     * @param AddItem $command
     * @throws ListNotFound
     */
    public function __invoke(AddItem $command): void
    {
        $list = $this->listRepository->get($command->listId());
        if ($list === null) {
            throw ListNotFound::withListId($command->listId());
        }

        $this->itemRepository->save(
            Item::add(
                $command->aggregateId(),
                $command->title(),
                $command->position(),
                $command->listId()
            )
        );
    }
}
