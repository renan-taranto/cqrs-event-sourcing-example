<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Board\Domain;

use Codeception\Specify;
use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Board\Domain\Event\BoardCreated;
use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Board\Domain\Event\BoardTitleChanged;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class BoardTest
 * @package Taranto\ListMaker\Tests\Board\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardTest extends Unit
{
    use Specify;

    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var Title
     */
    private $changedTitle;

    /**
     * @var Board
     * @specify
     */
    private $board;

    protected function _before(): void
    {
        $this->boardId = BoardId::generate();
        $this->title = Title::fromString("To-Dos");
        $this->changedTitle = Title::fromString("Best Practices");
        $this->board = Board::create($this->boardId, $this->title);
    }

    /**
     * @test
     */
    public function create(): void
    {
        $this->describe("Create", function() {
            $this->should("have recorded one event", function() {
                expect($this->board->popRecordedEvents())->count(1);
            });
            $this->should("have recorded a BoardCreated event", function() {
                /** @var BoardCreated $event */
                $event = $this->board->popRecordedEvents()[0];

                expect($event)->isInstanceOf(BoardCreated::class);
                expect($event->aggregateId())->equals($this->boardId);
                expect($event->title())->equals($this->title);
            });
        });
    }

    /**
     * @test
     */
    public function changeTitle(): void
    {
        $this->describe("Change Title", function() {
            $this->beforeSpecify(function () {
                $this->board->popRecordedEvents();
            });
            $this->should("have recorded one event", function() {
                $this->board->changeTitle($this->changedTitle);
                expect($this->board->popRecordedEvents())->count(1);
            });
            $this->should("have recorded a BoardTitleChanged event", function() {
                $this->board->changeTitle($this->changedTitle);
                /** @var BoardTitleChanged $event */
                $event = $this->board->popRecordedEvents()[0];

                expect($event)->isInstanceOf(BoardTitleChanged::class);
                expect($event->aggregateId())->equals($this->boardId);
                expect($event->title())->equals($this->changedTitle);
            });
        });
    }

    /**
     * @test
     */
    public function close(): void
    {
        $this->describe("Close", function() {
            $this->beforeSpecify(function () {
                $this->board->popRecordedEvents();
            });
            $this->should("have recorded one event", function() {
                $this->board->close();
                expect($this->board->popRecordedEvents())->count(1);
            });
            $this->should("have recorded a BoardClosed event", function() {
                $this->board->close();

                $event = $this->board->popRecordedEvents()[0];
                expect($event)->isInstanceOf(BoardClosed::class);
                expect($event->aggregateId())->equals($this->boardId);
            });
            $this->should("not record another BoardClosed event when already closed", function() {
                $this->board->close();
                $this->board->popRecordedEvents();

                $this->board->close();

                expect($this->board->popRecordedEvents())->count(0);
            });
        });
    }

    /**
     * @test
     */
    public function reopen(): void
    {
        $this->describe("Reopen", function() {
            $this->beforeSpecify(function () {
                $this->board->close();
                $this->board->popRecordedEvents();
            });
            $this->should("have recorded one event", function () {
                $this->board->reopen();
                expect($this->board->popRecordedEvents())->count(1);
            });
            $this->should("have recorded a BoardReopened event", function() {
                $this->board->reopen();
                /** @var BoardReopened $event */
                $event = $this->board->popRecordedEvents()[0];

                expect($event)->isInstanceOf(BoardReopened::class);
                expect($event->aggregateId())->equals($this->boardId);
            });
            $this->should("not record another BoardReopened event when already open", function() {
                $this->board->reopen();
                $this->board->popRecordedEvents();

                $this->board->reopen();

                expect($this->board->popRecordedEvents())->count(0);
            });
        });
    }
}
