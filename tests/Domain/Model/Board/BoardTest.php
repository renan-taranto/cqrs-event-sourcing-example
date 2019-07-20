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
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleChanged;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardReopened;
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

    protected function setUp(): void
    {
        $this->boardId = BoardId::generate();
        $this->title = Title::fromString("To-Dos");
    }

    /**
     * @test
     */
    public function it_is_created(): void
    {
        $board = Board::create($this->boardId, $this->title);

        $events = $board->popRecordedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(BoardCreated::class, get_class($events[0]));
        /** @var BoardCreated $boardCreated */
        $boardCreated = $events[0];
        $this->assertTrue($this->boardId->equals($boardCreated->aggregateId()));
        $this->assertTrue($this->title->equals($boardCreated->title()));
    }

    /**
     * @test
     */
    public function it_changes_its_title(): void
    {
        $board = Board::create($this->boardId, $this->title);

        $updatedTitle = Title::fromString("Best Practices");
        $board->changeTitle($updatedTitle);
        $events = $board->popRecordedEvents();

        $this->assertCount(2, $events);
        $this->assertSame(BoardCreated::class, get_class($events[0]));
        $this->assertSame(BoardTitleChanged::class, get_class($events[1]));
        /** @var BoardTitleChanged $boardTitleChanged */
        $boardTitleChanged = $events[1];
        $this->assertTrue($this->boardId->equals($boardTitleChanged->aggregateId()));
        $this->assertTrue($updatedTitle->equals($boardTitleChanged->title()));
    }

    /**
     * @test
     */
    public function it_can_be_closed(): void
    {
        $board = Board::create($this->boardId, $this->title);

        $board->close();
        $events = $board->popRecordedEvents();

        $this->assertCount(2, $events);
        $this->assertSame(BoardCreated::class, get_class($events[0]));
        $this->assertSame(BoardClosed::class, get_class($events[1]));
        /** @var BoardClosed $boardClosed */
        $boardClosed = $events[1];
        $this->assertTrue($this->boardId->equals($boardClosed->aggregateId()));
    }

    /**
     * @test
     */
    public function it_does_not_record_an_event_when_closing_if_already_closed(): void
    {
        $board = Board::create($this->boardId, $this->title);

        $board->close();
        $board->close();
        $events = $board->popRecordedEvents();

        $this->assertCount(2, $events);
        $this->assertSame(BoardCreated::class, get_class($events[0]));
        $this->assertSame(BoardClosed::class, get_class($events[1]));
    }

    /**
     * @test
     */
    public function it_can_be_reopened(): void
    {
        $board = Board::create($this->boardId, $this->title);

        $board->close();
        $board->reopen();
        $events = $board->popRecordedEvents();

        $this->assertCount(3, $events);
        $this->assertSame(BoardCreated::class, get_class($events[0]));
        $this->assertSame(BoardClosed::class, get_class($events[1]));
        $this->assertSame(BoardReopened::class, get_class($events[2]));
        /** @var BoardReopened $boardReopened */
        $boardReopened = $events[2];
        $this->assertTrue($this->boardId->equals($boardReopened->aggregateId()));
    }

    /**
     * @test
     */
    public function it_does_not_record_an_event_when_reopening_if_already_opened(): void
    {
        $board = Board::create($this->boardId, $this->title);

        $board->reopen();
        $events = $board->popRecordedEvents();

        $this->assertCount(1, $events);
        $this->assertSame(BoardCreated::class, get_class($events[0]));
    }
}
