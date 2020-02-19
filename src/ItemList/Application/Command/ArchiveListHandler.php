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
 * Class ArchiveListHandler
 * @package Taranto\ListMaker\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ArchiveListHandler
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * ArchiveListHandler constructor.
     * @param ListRepository $listRepository
     */
    public function __construct(ListRepository $listRepository)
    {
        $this->listRepository = $listRepository;
    }

    /**
     * @param ArchiveList $command
     * @throws ListNotFound
     */
    public function __invoke(ArchiveList $command): void
    {
        $list = $this->listRepository->get($command->aggregateId());
        if ($list === null) {
            throw ListNotFound::withListId($command->aggregateId());
        }

        $list->archive();
        $this->listRepository->save($list);
    }
}
