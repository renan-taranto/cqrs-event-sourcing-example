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

use Taranto\ListMaker\Item\Domain\Exception\ItemNotFound;
use Taranto\ListMaker\Item\Domain\ItemRepository;

/**
 * Class ChangeItemDescriptionHandler
 * @package Taranto\ListMaker\Item\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ChangeItemDescriptionHandler
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * ChangeItemDescriptionHandler constructor.
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param ChangeItemDescription $command
     * @throws ItemNotFound
     */
    public function __invoke(ChangeItemDescription $command): void
    {
        $item = $this->itemRepository->get($command->aggregateId());
        if ($item === null) {
            throw ItemNotFound::withItemId($command->aggregateId());
        }

        $item->changeDescription($command->description());
        $this->itemRepository->save($item);
    }
}
