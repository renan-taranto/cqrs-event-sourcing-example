<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use MongoDB\Collection;
use Taranto\ListMaker\Item\Infrastructure\Persistence\Projection\ItemProjector;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class MongoItemProjectionTest
 * @package Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemProjectorTest extends Unit
{
    use ItemEventsProvider;

    private const ITEM_ID = 'c8f94b93-a41d-490d-85e0-47990bc4792f';
    private const ARCHIVED_ITEM_ID = 'a7bb5c80-0b83-41f2-83cc-b1477a298434';
    private const LIST_ID = '197c76a8-dcd9-473e-afd8-3ea6556484f3';

    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var ItemProjector
     */
    private $projector;

    /**
     * @var Collection
     */
    private $boardsCollection;

    protected function _before(): void
    {
        $this->projector = $this->tester->grabService('test.service_container')->get(ItemProjector::class);
        $this->boardsCollection = $this->tester->grabService('mongo.collection.boards');
    }

    /**
     * @test
     */
    public function it_adds_an_item(): void
    {
        $itemAdded = $this->itemAddedEvent(self::LIST_ID);

        ($this->projector)($itemAdded);


        $item = $this->itemById((string) $itemAdded->aggregateId());
        expect($item)->equals([
            'id' => (string) $itemAdded->aggregateId(),
            'title' => (string) $itemAdded->title(),
            'description' => ''
        ]);
    }

    /**
     * @test
     */
    public function it_archives_an_item(): void
    {
        $item = $this->itemById(self::ITEM_ID);
        $itemArchived = $this->itemArchivedEvent(self::ITEM_ID);

        ($this->projector)($itemArchived);

        $archivedItem = $this->archivedItemById(self::ITEM_ID);
        expect($archivedItem)->equals($item);
    }

    /**
     * @test
     */
    public function it_restores_an_item(): void
    {
        $archivedItem = $this->archivedItemById(self::ARCHIVED_ITEM_ID);
        $itemRestored = $this->itemRestoredEvent(self::ARCHIVED_ITEM_ID);

        ($this->projector)($itemRestored);

        $item = $this->itemById(self::ARCHIVED_ITEM_ID);
        expect($item)->equals($archivedItem);
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_an_item(): void
    {
        $itemTitleChanged = $this->itemTitleChanged(self::ITEM_ID);

        ($this->projector)($itemTitleChanged);

        $item = $this->itemById(self::ITEM_ID);
        expect($item['title'])->equals((string) $itemTitleChanged->title());
    }

    /**
     * @test
     */
    public function it_changes_the_description_of_an_item(): void
    {
        $itemDescriptionChanged = $this->itemDescriptionChanged(self::ITEM_ID);

        ($this->projector)($itemDescriptionChanged);

        $item = $this->itemById(self::ITEM_ID);
        expect($item['description'])->equals($itemDescriptionChanged->description());
    }

    /**
     * @test
     */
    public function it_moves_the_item_in_the_same_list(): void
    {
        $item = $this->itemById(self::ITEM_ID);
        $updatedPosition = 1;
        $itemMoved = $this->itemMovedEvent(self::ITEM_ID, $updatedPosition, self::LIST_ID);

        ($this->projector)($itemMoved);

        $updatedItem = $this->itemByListIdAndPosition(self::LIST_ID, $updatedPosition);
        expect($updatedItem)->equals($item);
    }

    /**
     * @test
     */
    public function it_moves_the_item_to_another_list(): void
    {
        $listId = '78a03a97-6643-4940-853b-0c89ada22bf2';
        $item = $this->itemById(self::ITEM_ID);
        $updatedPosition = 0;
        $itemMoved = $this->itemMovedEvent(self::ITEM_ID, $updatedPosition, $listId);

        ($this->projector)($itemMoved);

        $updatedItem = $this->itemByListIdAndPosition($listId, $updatedPosition);
        expect($updatedItem)->equals($item);
    }

    /**
     * @param string $id
     * @return array
     */
    private function itemById(string $id): array
    {
        return $this->boardsCollection->aggregate(
            [
                ['$unwind' => '$lists'],
                ['$unwind' => '$lists.items'],
                ['$match' => ['lists.items.id' => $id]],
                ['$project' => ['lists.items' => true, '_id' => false]]
            ]
        )->toArray()[0]['lists']['items'];
    }

    /**
     * @param string $id
     * @return array
     */
    private function archivedItemById(string $id): array
    {
        return $this->boardsCollection->aggregate(
            [
                ['$unwind' => '$lists'],
                ['$unwind' => '$lists.archivedItems'],
                ['$match' => ['lists.archivedItems.id' => $id]],
                ['$project' => ['lists.archivedItems' => true, '_id' => false]]
            ]
        )->toArray()[0]['lists']['archivedItems'];
    }

    /**
     * @param string $listId
     * @param int $position
     * @return array
     */
    private function itemByListIdAndPosition(string $listId, int $position): array
    {
        return $this->boardsCollection->findOne(
            ['lists.id' => $listId],
            ['projection' => ['lists.$' => true, '_id' => false]]
        )['lists'][0]['items'][$position];
    }
}
