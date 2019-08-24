<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Application\Query\Data;

/**
 * Interface BoardFinder
 * @package Taranto\ListMaker\Board\Application\Query\Data
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface BoardFinder
{
    /**
     * @return BoardData[]
     */
    public function openBoards(): array;

    /**
     * @return BoardData[]
     */
    public function closedBoards(): array;

    /**
     * @param string $boardId
     * @return BoardData|null
     */
    public function boardById(string $boardId): ?BoardData;
}
