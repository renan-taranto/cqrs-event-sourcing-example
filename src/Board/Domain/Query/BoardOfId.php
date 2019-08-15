<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Domain\Query;

/**
 * Class BoardOfId
 * @package Taranto\ListMaker\Board\Domain\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardOfId
{
    /**
     * @var string
     */
    private $boardId;

    /**
     * BoardOfId constructor.
     * @param string $boardId
     */
    public function __construct(string $boardId)
    {
        $this->boardId = $boardId;
    }

    /**
     * @return string
     */
    public function boardId(): string
    {
        return $this->boardId;
    }
}
