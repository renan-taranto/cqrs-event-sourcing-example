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
use Taranto\ListMaker\Board\Domain\Query\BoardById;

/**
 * Class BoardByIdHandler
 * @package Taranto\ListMaker\Board\Application\QueryHandler
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardByIdHandler
{
    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * BoardOfIdHandler constructor.
     * @param BoardFinder $boardFinder
     */
    public function __construct(BoardFinder $boardFinder)
    {
        $this->boardFinder = $boardFinder;
    }

    /**
     * @param BoardById $query
     * @return BoardData|null
     */
    public function __invoke(BoardById $query): ?BoardData
    {
        if (null === $board = $this->boardFinder->boardById($query->boardId())) {
            return null;
        }

        return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
    }
}
