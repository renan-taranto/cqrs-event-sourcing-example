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

use Taranto\ListMaker\Board\Domain\BoardFinder;

/**
 * Class ClosedBoardsHandler
 * @package Taranto\ListMaker\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ClosedBoardsHandler
{
    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * ClosedBoardsHandler constructor.
     * @param BoardFinder $boardFinder
     */
    public function __construct(BoardFinder $boardFinder)
    {
        $this->boardFinder = $boardFinder;
    }

    /**
     * @param ClosedBoards $query
     * @return array
     */
    public function __invoke(ClosedBoards $query): array
    {
        return $this->boardFinder->closedBoards();
    }
}
