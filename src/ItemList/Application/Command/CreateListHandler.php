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

use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Domain\Exception\BoardNotFound;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListRepository;

/**
 * Class CreateListHandler
 * @package Taranto\ListMaker\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CreateListHandler
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * CreateListHandler constructor.
     * @param ListRepository $listRepository
     * @param BoardRepository $boardRepository
     */
    public function __construct(ListRepository $listRepository, BoardRepository $boardRepository)
    {
        $this->listRepository = $listRepository;
        $this->boardRepository = $boardRepository;
    }

    /**
     * @param CreateList $command
     * @throws BoardNotFound
     */
    public function __invoke(CreateList $command): void
    {
        $board = $this->boardRepository->get($command->boardId());
        if ($board === null) {
            throw BoardNotFound::withBoardId($command->boardId());
        }

        $this->listRepository->save(
            ItemList::create($command->aggregateId(), $command->title(), $command->boardId())
        );
    }
}
