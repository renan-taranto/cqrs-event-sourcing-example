<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Board\Domain;

use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Board\Domain\Event\BoardCreated;
use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Board\Domain\Event\BoardTitleChanged;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\AggregateRootTestCase;

/**
 * Class BoardTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardTest extends AggregateRootTestCase
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $changedTitle;

    protected function _before(): void
    {
        $this->id = (string) BoardId::generate();
        $this->title = "To-Dos";
        $this->changedTitle = "Best Practices";
    }

    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $this
            ->when(function() {
                return Board::create(BoardId::fromString($this->id), Title::fromString($this->title));
            })
            ->then([BoardCreated::occur($this->id, ['title' => $this->title])]);
    }

    /**
     * @test
     */
    public function title_can_be_changed(): void
    {
        $this
            ->withAggregateId(BoardId::fromString($this->id))
            ->given([BoardCreated::occur($this->id, ['title' => $this->title])])
            ->when(function (Board $board) {
                $board->changeTitle(Title::fromString($this->changedTitle));
            })
            ->then([BoardTitleChanged::occur($this->id, ['title' => $this->changedTitle])]);
    }

    /**
     * @test
     */
    public function changing_the_title_records_no_events_when_new_title_is_equals_old_title(): void
    {
        $this
            ->withAggregateId(BoardId::fromString($this->id))
            ->given([BoardCreated::occur($this->id, ['title' => $this->title])])
            ->when(function (Board $board) {
                $board->changeTitle(Title::fromString($this->title));
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_closed(): void
    {
        $this
            ->withAggregateId(BoardId::fromString($this->id))
            ->given([BoardCreated::occur($this->id, ['title' => $this->title])])
            ->when(function (Board $board) {
                $board->close();
            })
            ->then([BoardClosed::occur($this->id)]);
    }

    /**
     * @test
     */
    public function closing_a_closed_board_records_no_events(): void
    {
        $this
            ->withAggregateId(BoardId::fromString($this->id))
            ->given([
                BoardCreated::occur($this->id, ['title' => $this->title]),
                BoardClosed::occur($this->id)
            ])
            ->when(function (Board $board) {
                $board->close();
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_reopened(): void
    {
        $this
            ->withAggregateId(BoardId::fromString($this->id))
            ->given([
                BoardCreated::occur($this->id, ['title' => $this->title]),
                BoardClosed::occur($this->id)
            ])
            ->when(function (Board $board) {
                $board->reopen();
            })
            ->then([BoardReopened::occur($this->id)]);
    }

    /**
     * @test
     */
    public function reopening_an_open_board_records_no_events(): void
    {
        $this
            ->withAggregateId(BoardId::fromString($this->id))
            ->given([BoardCreated::occur($this->id, ['title' => $this->title])])
            ->when(function (Board $board) {
                $board->reopen();
            })
            ->then([]);
    }

    /**
     * @return string
     */
    protected function getAggregateRootClass(): string
    {
        return Board::class;
    }
}
