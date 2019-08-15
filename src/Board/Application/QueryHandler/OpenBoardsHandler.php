<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\QueryHandler;

use Taranto\ListMaker\Board\Application\QueryHandler\Data\BoardData;
use Taranto\ListMaker\Board\Domain\BoardFinder;
use Taranto\ListMaker\Board\Domain\Query\OpenBoards;

/**
 * Class OpenBoardsHandler
 * @package Taranto\ListMaker\Board\Application\QueryHandler
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
            return new BoardData($board['boardId'], $board['title']);
        }, $this->boardFinder->openBoards());
    }
}
