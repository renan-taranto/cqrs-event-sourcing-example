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
use Taranto\ListMaker\Board\Application\Query\Finder\BoardFinder as BoardFinderInterface;

/**
 * Class BoardFinder
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardFinder implements BoardFinderInterface
{
    /**
     * @var Collection
     */
    private $boardsCollection;

    /**
     * BoardFinder constructor.
     * @param Collection $boardsCollection
     */
    public function __construct(Collection $boardsCollection)
    {
        $this->boardsCollection = $boardsCollection;
    }

    /**
     * @return array
     */
    public function openBoards(): array
    {
        return $this->boardsCollection->find(['open' => true], ['projection' => ['_id' => false]])->toArray();
    }

    /**
     * @return array
     */
    public function closedBoards(): array
    {
        return $this->boardsCollection->find(['open' => false], ['projection' => ['_id' => false]])->toArray();
    }

    /**
     * @param string $boardId
     * @return array|null
     */
    public function byId(string $boardId): ?array
    {
        return $this->boardsCollection->findOne(
            ['id' => $boardId],
            ['projection' => ['_id' => false]]
        );
    }
}
