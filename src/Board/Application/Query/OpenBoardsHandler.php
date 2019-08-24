<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\Query;

use Taranto\ListMaker\Board\Application\Query\Data\BoardData;
use Taranto\ListMaker\Board\Domain\BoardFinder;

/**
 * Class OpenBoardsHandler
 * @package Taranto\ListMaker\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class OpenBoardsHandler
{
    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * OpenBoardsHandler constructor.
     * @param BoardFinder $boardFinder
     */
    public function __construct(BoardFinder $boardFinder)
    {
        $this->boardFinder = $boardFinder;
    }

    /**
     * @param OpenBoards $query
     * @return BoardData[]
     */
    public function __invoke(OpenBoards $query): array
    {
        return array_map(function ($board) {
            return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
        }, $this->boardFinder->openBoards());
    }
}
