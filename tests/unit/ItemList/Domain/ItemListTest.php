<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\ItemList\Domain;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListMoved;
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\AggregateRootTestCase;

/**
 * Class ItemListTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemListTest extends AggregateRootTestCase
{
    /**
     * @var string
     */
    private $listId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $boardId;

    protected function _before(): void
    {
        $this->listId = (string) ListId::generate();
        $this->title = 'To-Do';
        $this->position = 2;
        $this->boardId = (string) BoardId::generate();
    }

    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $this
            ->when(function () {
                return ItemList::create(
                    ListId::fromString($this->listId),
                    Title::fromString($this->title),
                    Position::fromInt($this->position),
                    BoardId::fromString($this->boardId)
                );
            })
            ->then([new ListCreated($this->listId, $this->title, $this->position, $this->boardId)]);
    }

    /**
     * @test
     */
    public function title_can_be_changed(): void
    {
        $changedTitle = 'To Do';
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([new ListCreated($this->listId, $this->title, $this->position, $this->boardId)])
            ->when(function (ItemList $list) use ($changedTitle) {
                $list->changeTitle(Title::fromString($changedTitle));
            })
            ->then([new ListTitleChanged($this->listId, $changedTitle)]);
    }

    /**
     * @test
     */
    public function changing_title_records_no_events_when_new_title_equals_old_one(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([new ListCreated($this->listId, $this->title, $this->position, $this->boardId)])
            ->when(function (ItemList $list) {
                $list->changeTitle(Title::fromString($this->title));
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_archived(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([new ListCreated($this->listId, $this->title, $this->position, $this->boardId)])
            ->when(function (ItemList $list) {
                $list->archive();
            })
            ->then([new ListArchived($this->listId)]);
    }

    /**
     * @test
     */
    public function archiving_records_no_events_when_already_archived(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([
                new ListCreated($this->listId, $this->title, $this->position, $this->boardId),
                new ListArchived($this->listId)
            ])
            ->when(function (ItemList $list) {
                $list->archive();
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_restored(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([
                new ListCreated($this->listId, $this->title, $this->position, $this->boardId),
                new ListArchived($this->listId)
            ])
            ->when(function (ItemList $list) {
                $list->restore();
            })
            ->then([new ListRestored($this->listId)]);
    }


    /**
     * @test
     */
    public function restoring_records_no_event_when_not_archived(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([new ListCreated($this->listId, $this->title, $this->position, $this->boardId)])
            ->when(function (ItemList $list) {
                $list->restore();
            })
            ->then([]);
    }

    /**
     * @test
     */
    public function it_can_be_moved(): void
    {
        $position = Position::fromInt(3);
        $boardId = BoardId::generate();

        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([new ListCreated($this->listId, $this->title, $this->position, $this->boardId)])
            ->when(function (ItemList $list) use ($position, $boardId) {
                $list->move($position, $boardId);
            })
            ->then([new ListMoved($this->listId, $position->toInt(), (string) $boardId)]);
    }

    /**
     * @test
     */
    public function moving_records_no_events_when_archived(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([
                new ListCreated($this->listId, $this->title, $this->position, $this->boardId),
                new ListArchived($this->listId)
            ])
            ->when(function (ItemList $list) {
                $list->move(Position::fromInt(2), BoardId::generate());
            })
            ->then([]);
    }

    /**
     * @return string
     */
    protected function getAggregateRootClass(): string
    {
        return ItemList::class;
    }
}
