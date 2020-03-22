<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\Query\Finder;

/**
 * Interface BoardOverviewFinder
 * @package Taranto\ListMaker\Board\Application\Query\Finder
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface BoardsOverviewFinder
{
    /**
     * @param string $boardId
     * @return array
     */
    public function byBoardId(string $boardId): array;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @return array
     */
    public function allOpenBoards(): array;

    /**
     * @return array
     */
    public function allClosedBoards(): array;
}
