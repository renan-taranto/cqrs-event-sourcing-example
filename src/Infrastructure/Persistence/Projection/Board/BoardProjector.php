<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Persistence\Projection\Board;

use Taranto\ListMaker\Domain\Model\Board\Event\BoardClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardReopened;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleChanged;
use Taranto\ListMaker\Infrastructure\Persistence\Projection\Projector;

/**
 * Class BoardProjector
 * @package Taranto\ListMaker\Infrastructure\Persistence\Projection\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardProjector extends Projector
{
    /**
     * @var BoardProjection
     */
    private $projection;

    /**
     * BoardProjector constructor.
     * @param BoardProjection $projection
     */
    public function __construct(BoardProjection $projection)
    {
        $this->projection = $projection;
    }

    /**
     * @param BoardCreated $event
     */
    protected function projectBoardCreated(BoardCreated $event): void
    {
        $this->projection->createBoard($event->aggregateId(), $event->title());
    }

    /**
     * @param BoardTitleChanged $event
     */
    protected function projectBoardTitleChanged(BoardTitleChanged $event): void
    {
        $this->projection->changeBoardTitle($event->aggregateId(), $event->title());
    }

    /**
     * @param BoardClosed $event
     */
    protected function projectBoardClosed(BoardClosed $event): void
    {
        $this->projection->closeBoard($event->aggregateId());
    }

    /**
     * @param BoardReopened $event
     */
    protected function projectBoardReopened(BoardReopened $event): void
    {
        $this->projection->reopenBoard($event->aggregateId());
    }
}
