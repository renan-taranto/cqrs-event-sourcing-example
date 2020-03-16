<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Item\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
use Taranto\ListMaker\Item\Domain\Event\ItemReordered;
use Taranto\ListMaker\Item\Domain\Event\ItemRestored;
use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\Persistence\Projection\ItemProjection;
use Taranto\ListMaker\Item\Infrastructure\Persistence\Projection\ItemProjector;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Hamcrest\Core\IsEqual;

/**
 * Class ItemProjectorTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemProjectorTest extends Unit
{
    /**
     * @var ItemProjection
     */
    private $projection;

    /**
     * @var ItemProjector
     */
    private $projector;

    /**
     * @var ItemAdded
     */
    private $itemAdded;

    /**
     * @var ItemArchived
     */
    private $itemArchived;

    /**
     * @var ItemDescriptionChanged
     */
    private $itemDescriptionChanged;

    /**
     * @var ItemReordered
     */
    private $itemReordered;

    /**
     * @var ItemRestored
     */
    private $itemRestored;

    /**
     * @var ItemTitleChanged
     */
    private $itemTitleChanged;

    /**
     * @var ItemMoved
     */
    private $itemMoved;

    protected function _before()
    {
        $this->projection = \Mockery::spy(ItemProjection::class);
        $this->projector = new ItemProjector($this->projection);

        $itemId = (string) ItemId::generate();
        $listId = (string) ListId::generate();
        $this->itemAdded = ItemAdded::occur(
            $itemId,
            ['title' => 'Feature: Items', 'position' => 4, 'listId' => $listId]
        );
        $this->itemArchived = ItemArchived::occur($itemId);
        $this->itemDescriptionChanged = ItemDescriptionChanged::occur(
            $itemId,
            ['description' => 'In order to...']
        );
        $this->itemRestored = ItemRestored::occur($itemId);
        $this->itemTitleChanged = ItemTitleChanged::occur($itemId, ['title' => 'As an API user...']);
        $this->itemMoved = ItemMoved::occur($itemId, ['position' => 3, 'listId' => $listId]);
    }

    /**
     * @test
     */
    public function it_projects_the_ItemAdded_event(): void
    {
        ($this->projector)($this->itemAdded);
        $this->projection->shouldHaveReceived('addItem')
            ->with(
                isEqual::equalTo($this->itemAdded->aggregateId()),
                isEqual::equalTo($this->itemAdded->title()),
                isEqual::equalTo($this->itemAdded->position()),
                isEqual::equalTo($this->itemAdded->listId())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_ItemArchived_event(): void
    {
        ($this->projector)($this->itemArchived);
        $this->projection->shouldHaveReceived('archiveItem')
            ->with(isEqual::equalTo($this->itemArchived->aggregateId()));
    }

    /**
     * @test
     */
    public function it_projects_the_ItemDescriptionChanged_event(): void
    {
        ($this->projector)($this->itemDescriptionChanged);
        $this->projection->shouldHaveReceived('changeItemDescription')
            ->with(
                isEqual::equalTo($this->itemDescriptionChanged->aggregateId()),
                isEqual::equalTo($this->itemDescriptionChanged->description())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_ItemRestored_event(): void
    {
        ($this->projector)($this->itemRestored);
        $this->projection->shouldHaveReceived('restoreItem')
            ->with(isEqual::equalTo($this->itemRestored->aggregateId()));
    }

    /**
     * @test
     */
    public function it_projects_the_ItemTitleChanged_event(): void
    {
        ($this->projector)($this->itemTitleChanged);
        $this->projection->shouldHaveReceived('changeItemTitle')
            ->with(
                isEqual::equalTo($this->itemTitleChanged->aggregateId()),
                isEqual::equalTo($this->itemTitleChanged->title())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_ItemMoved_event(): void
    {
        ($this->projector)($this->itemMoved);
        $this->projection->shouldHaveReceived('moveItem')
            ->with(
                isEqual::equalTo($this->itemMoved->aggregateId()),
                isEqual::equalTo($this->itemMoved->position()),
                isEqual::equalTo($this->itemMoved->listId())
            );
    }
}
