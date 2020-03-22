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
 * Class BoardsOverviewProjector
 * @package Taranto\ListMaker\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardsOverviewProjector extends Projector
{
    /**
     * @var Collection
     */
    private $boardsOverviewCollection;

    /**
     * BoardOverviewProjector constructor.
     * @param Collection $boardsOverviewCollection
     */
    public function __construct(Collection $boardsOverviewCollection)
    {
        $this->boardsOverviewCollection = $boardsOverviewCollection;
    }

    /**
     * @param BoardCreated $event
     */
    protected function projectBoardCreated(BoardCreated $event): void
    {
        $this->boardsOverviewCollection->insertOne([
            'id' => (string) $event->aggregateId(),
            'title' => (string) $event->title(),
            'open' => true
        ]);
    }

    /**
     * @param BoardTitleChanged $event
     */
    protected function projectBoardTitleChanged(BoardTitleChanged $event): void
    {
        $this->boardsOverviewCollection->updateOne(
            ['id' => (string) $event->aggregateId()],
            ['$set' => ['title' => (string) $event->title()]]
        );
    }

    /**
     * @param BoardClosed $event
     */
    protected function projectBoardClosed(BoardClosed $event): void
    {
        $this->boardsOverviewCollection->updateOne(
            ['id' => (string) $event->aggregateId()],
            ['$set' => ['open' => false]]
        );
    }

    /**
     * @param BoardReopened $event
     */
    protected function projectBoardReopened(BoardReopened $event): void
    {
        $this->boardsOverviewCollection->updateOne(
            ['id' => (string) $event->aggregateId()],
            ['$set' => ['open' => true]]
        );
    }
}
