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

use Taranto\ListMaker\Domain\Aggregate\AggregateRoot;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleWasChanged;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardWasClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardWasCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardWasReopened;
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
            BoardWasCreated::occur((string) $boardId, ['title' => (string) $title])
        );

        return $instance;
    }

    /**
     * @param BoardWasCreated $event
     */
    protected function whenBoardWasCreated(BoardWasCreated $event): void
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
            BoardTitleWasChanged::occur((string) $this->boardId, ['title' => (string) $title])
        );
    }

    /**
     * @param BoardTitleWasChanged $event
     */
    protected function whenBoardTitleWasChanged(BoardTitleWasChanged $event): void
    {
    }

    public function close(): void
    {
       if (!$this->isOpen) {
           return;
       }

       $this->recordThat(BoardWasClosed::occur((string) $this->boardId));
    }

    /**
     * @param BoardWasClosed $event
     */
    protected function whenBoardWasClosed(BoardWasClosed $event): void
    {
        $this->isOpen = false;
    }

    public function reopen(): void
    {
        if ($this->isOpen) {
            return;
        }

        $this->recordThat(BoardWasReopened::occur((string) $this->boardId));
    }

    /**
     * @param BoardWasReopened $event
     */
    protected function whenBoardWasReopened(BoardWasReopened $event): void
    {
        $this->isOpen = true;
    }
}
