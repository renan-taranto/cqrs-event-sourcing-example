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

use Taranto\ListMaker\Board\Application\Query\Finder\BoardFinder;

/**
 * Class BoardByIdHandler
 * @package Taranto\ListMaker\Board\Application\Query
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
     * @return array|null
     */
    public function __invoke(BoardById $query): ?array
    {
        return $this->boardFinder->byId($query->boardId());
    }
}
