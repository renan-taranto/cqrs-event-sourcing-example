<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Board;

/**
 * Interface BoardRepository
 * @package Taranto\ListMaker\Domain\Model\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface BoardRepository
{
    /**
     * @param Board $board
     */
    public function save(Board $board): void;

    /**
     * @param BoardId $boardId
     * @return Board|null
     */
    public function get(BoardId $boardId): ?Board;
}
