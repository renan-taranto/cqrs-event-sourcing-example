<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Board;

use Taranto\ListMaker\Domain\Model\Common\AggregateRoot;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleChanged;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardReopened;
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;

/**
 * Class Board
 * @package Taranto\ListMaker\Domain\Model\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class Board extends AggregateRoot
{
    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var bool
     */
    private $isOpen;

    /**
     * @param BoardId $boardId
     * @param Title $title
     * @return Board
     */
    public static function create(BoardId $boardId, Title $title): self
    {
        $instance = new self();

        $instance->recordThat(
            BoardCreated::occur((string) $boardId, ['title' => (string) $title])
        );

        return $instance;
    }

    /**
     * @param BoardCreated $event
     */
    protected function whenBoardCreated(BoardCreated $event): void
    {
        $this->boardId = $event->aggregateId();
        $this->isOpen = true;
    }

    /**
     * @param Title $title
     */
    public function changeTitle(Title $title): void
    {
        $this->recordThat(
            BoardTitleChanged::occur((string) $this->boardId, ['title' => (string) $title])
        );
    }

    /**
     * @param BoardTitleChanged $event
     */
    protected function whenBoardTitleChanged(BoardTitleChanged $event): void
    {
    }

    public function close(): void
    {
       if (!$this->isOpen) {
           return;
       }

       $this->recordThat(BoardClosed::occur((string) $this->boardId));
    }

    /**
     * @param BoardClosed $event
     */
    protected function whenBoardClosed(BoardClosed $event): void
    {
        $this->isOpen = false;
    }

    public function reopen(): void
    {
        if ($this->isOpen) {
            return;
        }

        $this->recordThat(BoardReopened::occur((string) $this->boardId));
    }

    /**
     * @param BoardReopened $event
     */
    protected function whenBoardReopened(BoardReopened $event): void
    {
        $this->isOpen = true;
    }
}
