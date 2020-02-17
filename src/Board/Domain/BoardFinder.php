<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Domain;

/**
 * Interface BoardFinder
 * @package Taranto\ListMaker\Board\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface BoardFinder
{
    /**
     * @return array
     */
    public function openBoards(): array;

    /**
     * @return array
     */
    public function closedBoards(): array;

    /**
     * @param string $boardId
     * @return array|null
     */
    public function byId(string $boardId): ?array;
}
