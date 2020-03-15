<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Application\Command;

use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ListRepository;

/**
 * Class MoveListHandler
 * @package Taranto\ListMaker\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MoveListHandler
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * MoveListHandler constructor.
     * @param ListRepository $listRepository
     */
    public function __construct(ListRepository $listRepository)
    {
        $this->listRepository = $listRepository;
    }

    /**
     * @param MoveList $command
     * @throws ListNotFound
     */
    public function __invoke(MoveList $command): void
    {
        $list = $this->listRepository->get($command->aggregateId());
        if ($list === null) {
            throw ListNotFound::withListId($command->aggregateId());
        }

        $list->move($command->position(), $command->boardId());
        $this->listRepository->save($list);
    }
}
