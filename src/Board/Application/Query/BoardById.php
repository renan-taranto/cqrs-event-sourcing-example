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

/**
 * Class BoardById
 * @package Taranto\ListMaker\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardById
{
    /**
     * @var string|null
     */
    private $boardId;

    /**
     * BoardById constructor.
     * @param string|null $boardId
     */
    public function __construct(string $boardId = null)
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
