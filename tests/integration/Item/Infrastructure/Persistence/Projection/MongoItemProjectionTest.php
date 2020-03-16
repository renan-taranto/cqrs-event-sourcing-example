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
use Taranto\ListMaker\Board\Domain\BoardFinder;
use Taranto\ListMaker\Item\Domain\Description;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\Persistence\Projection\ItemProjection;
use Taranto\ListMaker\Item\Infrastructure\Persistence\Projection\MongoItemProjection;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class MongoItemProjectionTest
 * @package Taranto\ListMaker\Tests\Integration\Item\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MongoItemProjectionTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var ItemProjection
     */
    private $itemProjection;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    protected function _before()
    {
        $this->itemProjection = $this->tester->grabService('test.service_container')->get(MongoItemProjection::class);
        $this->boardFinder = $this->tester->grabService('test.service_container')->get(BoardFinder::class);
    }

    /**
     * @test
     */
    public function it_adds_an_item(): void
    {
        $itemId = ItemId::generate();
        $title = 'Feature: Items';
        $position = 0;
        $listId = $this->findList(0, 0)['id'];

        $this->itemProjection->addItem(
            $itemId,
            Title::fromString($title),
            Position::fromInt($position),
            ListId::fromString($listId)
        );

        $item = $this->findItem(0, 0, $position);
        expect($item)->equals([
            'id' => (string) $itemId,
            'title' => $title,
            'description' => ''
        ]);
    }

    /**
     * @test
     */
    public function it_archives_an_item(): void
    {
        $item = $this->findItem(0, 0, 0);

        $this->itemProjection->archiveItem(ItemId::fromString($item['id']));

        $archivedItem = end($this->findList(0, 0)['archivedItems']);
        expect($archivedItem)->equals($item);
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_an_item(): void
    {
        $item = $this->findItem(0, 0, 0);
        $newTitle = '[WIP] Feature: Items';

        $this->itemProjection->changeItemTitle(
            ItemId::fromString($item['id']),
            Title::fromString($newTitle)
        );

        $updatedItem = $this->findItem(0, 0, 0);
        expect($updatedItem['title'])->equals($newTitle);
    }

    /**
     * @test
     */
    public function it_restores_an_item(): void
    {
        $itemToBeRestored = $this->findList(0, 0)['archivedItems'][0];

        $this->itemProjection->restoreItem(ItemId::fromString($itemToBeRestored['id']));

        $restoredItem = end($this->findList(0, 0)['items']);
        expect($restoredItem)->equals($itemToBeRestored);
    }

    /**
     * @test
     */
    public function it_changes_the_description_of_an_item(): void
    {
        $item = $this->findItem(0, 0 , 0);
        $newDescription = 'As an API user...';

        $this->itemProjection->changeItemDescription(
            ItemId::fromString($item['id']),
            Description::fromString($newDescription)
        );

        $updatedItem = $this->findItem(0, 0 , 0);
        expect($updatedItem['description'])->equals($newDescription);
    }

    /**
     * @test
     */
    public function it_moves_the_item_in_the_same_list(): void
    {
        $item = $this->findItem(0, 0 , 2);
        $position = 1;
        $listId = $this->findList(0, 0)['id'];

        $this->itemProjection->moveItem(
            ItemId::fromString($item['id']),
            Position::fromInt($position),
            ListId::fromString($listId)
        );

        $updatedItem = $this->findItem(0, 0 , $position);
        expect($updatedItem)->equals($item);
    }

    /**
     * @test
     */
    public function it_moves_the_item_to_another_list(): void
    {
        $item = $this->findItem(0, 0 , 2);
        $position = 0;
        $listId = $this->findList(0, 1)['id'];

        $this->itemProjection->moveItem(
            ItemId::fromString($item['id']),
            Position::fromInt($position),
            ListId::fromString($listId)
        );

        $updatedItem = $this->findItem(0, 1 , $position);
        expect($updatedItem)->equals($item);
    }

    /**
     * @param int $boardIndex
     * @param int $listIndex
     * @return array
     */
    private function findList(int $boardIndex, int $listIndex): array
    {
        return $this->boardFinder->openBoards()[$boardIndex]['lists'][$listIndex];
    }

    /**
     * @param int $boardIndex
     * @param int $listIndex
     * @param int $itemIndex
     * @return array
     */
    private function findItem(int $boardIndex, int $listIndex, int $itemIndex): array
    {
        return $this->boardFinder->openBoards()[$boardIndex]['lists'][$listIndex]['items'][$itemIndex];
    }
}
