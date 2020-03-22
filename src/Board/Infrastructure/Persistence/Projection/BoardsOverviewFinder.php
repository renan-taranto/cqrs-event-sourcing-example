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
use Taranto\ListMaker\Board\Application\Query\Finder\BoardsOverviewFinder as BoardsOverviewFinderInterface;

/**
 * Class BoardsOverviewFinder
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardsOverviewFinder implements BoardsOverviewFinderInterface
{
    /**
     * @var Collection
     */
    private $boardsOverviewCollection;

    /**
     * BoardOverviewFinder constructor.
     * @param Collection $boardsOverviewCollection
     */
    public function __construct(Collection $boardsOverviewCollection)
    {
        $this->boardsOverviewCollection = $boardsOverviewCollection;
    }

    /**
     * @param string $boardId
     * @return array
     */
    public function byBoardId(string $boardId): array
    {
        return $this->boardsOverviewCollection->findOne(['id' => $boardId], ['projection' => ['_id' => false]]);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->boardsOverviewCollection->find([], ['projection' => ['_id' => false]])->toArray();
    }

    /**
     * @return array
     */
    public function allOpenBoards(): array
    {
        return $this->boardsOverviewCollection->find(['open' => true], ['projection' => ['_id' => false]])->toArray();
    }

    /**
     * @return array
     */
    public function allClosedBoards(): array
    {
        return $this->boardsOverviewCollection->find(['open' => false], ['projection' => ['_id' => false]])->toArray();
    }
}
