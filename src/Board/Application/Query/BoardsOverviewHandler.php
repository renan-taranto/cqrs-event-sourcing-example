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

use Taranto\ListMaker\Board\Application\Query\Finder\BoardsOverviewFinder;

/**
 * Class BoardsOverviewHandler
 * @package Taranto\ListMaker\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardsOverviewHandler
{
    /**
     * @var BoardsOverviewFinder
     */
    private $boardOverviewFinder;

    /**
     * BoardsOverviewHandler constructor.
     * @param BoardsOverviewFinder $boardOverviewFinder
     */
    public function __construct(BoardsOverviewFinder $boardOverviewFinder)
    {
        $this->boardOverviewFinder = $boardOverviewFinder;
    }

    /**
     * @param BoardsOverview $query
     * @return array
     */
    public function __invoke(BoardsOverview $query): array
    {
        if ($query->open() === null) {
            return $this->boardOverviewFinder->all();
        }

        return $query->open() ? $this->boardOverviewFinder->allOpenBoards() : $this->boardOverviewFinder->allClosedBoards();
    }
}