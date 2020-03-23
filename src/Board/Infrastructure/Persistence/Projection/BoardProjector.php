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
use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Board\Domain\Event\BoardCreated;
use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Board\Domain\Event\BoardTitleChanged;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\Projector;

/**
 * Class BoardProjector
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardProjector extends Projector
{
    /**
     * @var Collection
     */
    private $boardsCollection;

    /**
     * BoardProjector constructor.
     * @param Collection $boardsCollection
     */
    public function __construct(Collection $boardsCollection)
    {
        $this->boardsCollection = $boardsCollection;
    }

    /**
     * @param BoardCreated $event
     */
    protected function projectBoardCreated(BoardCreated $event): void
    {
        $this->boardsCollection->insertOne([
            'id' => (string) $event->aggregateId(),
            'title' => (string) $event->title(),
            'open' => true,
            'lists' => [],
            'archivedLists' => []
        ]);
    }

    /**
     * @param BoardTitleChanged $event
     */
    protected function projectBoardTitleChanged(BoardTitleChanged $event): void
    {
        $this->boardsCollection->updateOne(
            ['id' => (string) $event->aggregateId()],
            ['$set' => ['title' => (string) $event->title()]]
        );
    }

    /**
     * @param BoardClosed $event
     */
    protected function projectBoardClosed(BoardClosed $event): void
    {
        $this->boardsCollection->updateOne(
            ['id' => (string) $event->aggregateId()],
            ['$set' => ['open' => false]]
        );
    }

    /**
     * @param BoardReopened $event
     */
    protected function projectBoardReopened(BoardReopened $event): void
    {
        $this->boardsCollection->updateOne(
            ['id' => (string) $event->aggregateId()],
            ['$set' => ['open' => true]]
        );
    }
}
