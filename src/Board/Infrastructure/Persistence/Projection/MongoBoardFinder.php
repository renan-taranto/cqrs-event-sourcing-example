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
use Taranto\ListMaker\Board\Domain\BoardFinder;

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
     * @return array
     */
    public function openBoards(): array
    {
        return $this->boardCollection->find(['isOpen' => true])->toArray();
    }

    /**
     * @return array
     */
    public function closedBoards(): array
    {
        return $this->boardCollection->find(['isOpen' => false])->toArray();
    }

    /**
     * @param string $boardId
     * @return array|null
     */
    public function boardById(string $boardId): ?array
    {
        return $this->boardCollection->findOne(
            ['boardId' => $boardId],
            ['typeMap' => ['root' => 'array', 'document' => 'array']]
        );
    }
}
