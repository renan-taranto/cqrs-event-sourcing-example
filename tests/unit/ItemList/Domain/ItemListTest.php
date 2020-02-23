<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\ItemList\Domain;

use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListReordered;
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\AggregateRootTestCase;

/**
 * Class ItemListTest
 * @package Taranto\ListMaker\Tests\ItemList\Domain
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
            ->then([
                ListCreated::occur(
                    $this->listId,
                    ['title' => $this->title, 'position' => $this->position, 'boardId' => $this->boardId]
                )
            ]);
    }

    /**
     * @test
     */
    public function title_can_be_changed(): void
    {
        $changedTitle = 'To Do';
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([ListCreated::occur(
                $this->listId,
                ['title' => $this->title, 'position' => $this->position, 'boardId' => $this->boardId]
            )])
            ->when(function (ItemList $list) use ($changedTitle) {
                $list->changeTitle(Title::fromString($changedTitle));
            })
            ->then([ListTitleChanged::occur($this->listId, ['title' => $changedTitle])]);
    }

    /**
     * @test
     */
    public function it_can_be_archived(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([ListCreated::occur(
                $this->listId,
                ['title' => $this->title, 'position' => $this->position, 'boardId' => $this->boardId]
            )])
            ->when(function (ItemList $list) {
                $list->archive();
            })
            ->then([ListArchived::occur($this->listId)]);
    }

    /**
     * @test
     */
    public function it_can_be_restored(): void
    {
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([
                ListCreated::occur(
                    $this->listId,
                    ['title' => $this->title, 'position' => $this->position, 'boardId' => $this->boardId]
                ),
                ListArchived::occur($this->listId)
            ])
            ->when(function (ItemList $list) {
                $list->restore();
            })
            ->then([ListRestored::occur($this->listId)]);
    }

    /**
     * @test
     */
    public function it_can_be_reordered(): void
    {
        $toPosition = Position::fromInt(3);
        $this
            ->withAggregateId(ListId::fromString($this->listId))
            ->given([ListCreated::occur(
                $this->listId,
                ['title' => $this->title, 'position' => $this->position, 'boardId' => $this->boardId]
            )])
            ->when(function (ItemList $list) use ($toPosition) {
                $list->reorder($toPosition);
            })
            ->then([ListReordered::occur($this->listId, ['toPosition' => $toPosition->toInt()])]);
    }

    /**
     * @return string
     */
    protected function getAggregateRootClass(): string
    {
        return ItemList::class;
    }
}
