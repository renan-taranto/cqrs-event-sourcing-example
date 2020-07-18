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
 * Class BoardsOverview
 * @package Taranto\ListMaker\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardsOverview
{
    /**
     * @var bool|null
     */
    private $open;

    /**
     * BoardsOverview constructor.
     * @param bool|null $open
     */
    public function __construct(bool $open = null)
    {
        $this->open = $open;
    }

    /**
     * @return bool|null
     */
    public function open(): ?bool
    {
        return $this->open;
    }
}
