<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Domain\Model\Board;

use PHPUnit\Framework\TestCase;
use Taranto\ListMaker\Domain\Model\Board\Board;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleWasChanged;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardWasClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardWasCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardWasReopened;
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;

/**
 * Class BoardTest
 * @package Taranto\ListMaker\Tests\Domain\Model\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardTest extends TestCase
{
    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var Title
     */
    private $title;

    protected function setUp()
    {
        $this->boardId = BoardId::generate();
        $this->title = Title::fromString("To-Dos");
    }

    /**
     * @test
     */
    public function it_is_created()
    {
        $board = Board::create($this->boardId, $this->title);

        $events = $board->popRecordedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(BoardWasCreated::class, get_class($events[0]));
        /** @var BoardWasCreated $boardWasCreated */
        $boardWasCreated = $events[0];
        $this->assertTrue($this->boardId->equals($boardWasCreated->aggregateId()));
        $this->assertTrue($this->title->equals($boardWasCreated->title()));
    }

    /**
     * @test
     */
    public function it_changes_its_title()
    {
        $board = Board::create($this->boardId, $this->title);

        $updatedTitle = Title::fromString("Best Practices");
        $board->changeTitle($updatedTitle);
        $events = $board->popRecordedEvents();

        $this->assertCount(2, $events);
        $this->assertSame(BoardWasCreated::class, get_class($events[0]));
        $this->assertSame(BoardTitleWasChanged::class, get_class($events[1]));
        /** @var BoardTitleWasChanged $boardTitleWasChanged */
        $boardTitleWasChanged = $events[1];
        $this->assertTrue($this->boardId->equals($boardTitleWasChanged->aggregateId()));
        $this->assertTrue($updatedTitle->equals($boardTitleWasChanged->title()));
    }

    /**
     * @test
     */
    public function it_can_be_closed()
    {
        $board = Board::create($this->boardId, $this->title);

        $board->close();
        $events = $board->popRecordedEvents();

        $this->assertCount(2, $events);
        $this->assertSame(BoardWasCreated::class, get_class($events[0]));
        $this->assertSame(BoardWasClosed::class, get_class($events[1]));
        /** @var BoardWasClosed $boardWasClosed */
        $boardWasClosed = $events[1];
        $this->assertTrue($this->boardId->equals($boardWasClosed->aggregateId()));
    }

    /**
     * @test
     */
    public function it_does_not_record_an_event_when_closing_if_already_closed()
    {
        $board = Board::create($this->boardId, $this->title);

        $board->close();
        $board->close();
        $events = $board->popRecordedEvents();

        $this->assertCount(2, $events);
        $this->assertSame(BoardWasCreated::class, get_class($events[0]));
        $this->assertSame(BoardWasClosed::class, get_class($events[1]));
    }

    /**
     * @test
     */
    public function it_can_be_reopened()
    {
        $board = Board::create($this->boardId, $this->title);

        $board->close();
        $board->reopen();
        $events = $board->popRecordedEvents();

        $this->assertCount(3, $events);
        $this->assertSame(BoardWasCreated::class, get_class($events[0]));
        $this->assertSame(BoardWasClosed::class, get_class($events[1]));
        $this->assertSame(BoardWasReopened::class, get_class($events[2]));
        /** @var BoardWasReopened $boardWasReopened */
        $boardWasReopened = $events[2];
        $this->assertTrue($this->boardId->equals($boardWasReopened->aggregateId()));
    }

    /**
     * @test
     */
    public function it_does_not_record_an_event_when_reopening_if_already_opened()
    {
        $board = Board::create($this->boardId, $this->title);

        $board->reopen();
        $events = $board->popRecordedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(BoardWasCreated::class, get_class($events[0]));
    }
}
