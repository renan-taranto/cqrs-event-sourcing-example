<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Infrastructure\Persistence\Projection;

use MongoDB\Collection;
use MongoDB\Model\BSONDocument;
use Taranto\ListMaker\Board\Application\Query\Data\BoardData;
use Taranto\ListMaker\Board\Application\Query\Data\BoardFinder;

/**
 * Class MongoBoardFinder
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoBoardFinder implements BoardFinder
{
    /**
     * @var Collection
     */
    private $boardCollection;

    /**
     * BoardFinder constructor.
     * @param Collection $boardCollection
     */
    public function __construct(Collection $boardCollection)
    {
        $this->boardCollection = $boardCollection;
    }

    /**
     * @return BoardData[]
     */
    public function openBoards(): array
    {
        return array_map(function (BSONDocument $board) {
            return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
        }, $this->boardCollection->find(['isOpen' => true])->toArray());
    }

    /**
     * @return BoardData[]
     */
    public function closedBoards(): array
    {
        return array_map(function (BSONDocument $board) {
            return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
        }, $this->boardCollection->find(['isOpen' => false])->toArray());
    }

    /**
     * @param string $boardId
     * @return BoardData|null
     */
    public function boardById(string $boardId): ?BoardData
    {
        $board = $this->boardCollection->findOne(
            ['boardId' => $boardId],
            ['typeMap' => ['root' => 'array', 'document' => 'array']]
        );
        if ($board === null) {
            return null;
        }

        return new BoardData($board['boardId'], $board['title'], $board['isOpen']);
    }
}
