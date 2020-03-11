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
    private const BOARD_INDEX = 0;

    private const LIST_INDEX = 0;

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
        $listId = $this->findList()['id'];

        $this->itemProjection->addItem(
            $itemId,
            Title::fromString($title),
            Position::fromInt($position),
            ListId::fromString($listId)
        );

        $item = $this->findList()['items'][$position];
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
        $item = $this->findList()['items'][0];

        $this->itemProjection->archiveItem(ItemId::fromString($item['id']));

        $archivedItem = end($this->findList()['archivedItems']);
        expect($archivedItem)->equals($item);
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_an_item(): void
    {
        $item = $this->findList()['items'][0];
        $newTitle = '[WIP] Feature: Items';

        $this->itemProjection->changeItemTitle(
            ItemId::fromString($item['id']),
            Title::fromString($newTitle)
        );

        $updatedItem = $this->findList()['items'][0];
        expect($updatedItem['title'])->equals($newTitle);
    }

    /**
     * @test
     */
    public function it_reorders_an_item(): void
    {
        $item = $this->findList()['items'][0];
        $toPosition = 1;

        $this->itemProjection->reorderItem(
            ItemId::fromString($item['id']),
            Position::fromInt($toPosition)
        );

        $reorderedItem = $this->findList()['items'][$toPosition];
        expect($reorderedItem)->equals($item);
    }

    /**
     * @test
     */
    public function it_restores_an_item(): void
    {
        $itemToBeRestored = $this->findList()['archivedItems'][0];

        $this->itemProjection->restoreItem(ItemId::fromString($itemToBeRestored['id']));

        $restoredItem = end($this->findList()['items']);
        expect($restoredItem)->equals($itemToBeRestored);
    }

    /**
     * @test
     */
    public function it_changes_the_description_of_an_item(): void
    {
        $item = $this->findList()['items'][0];
        $newDescription = 'As an API user...';

        $this->itemProjection->changeItemDescription(
            ItemId::fromString($item['id']),
            Description::fromString($newDescription)
        );

        $updatedItem = $this->findList()['items'][0];
        expect($updatedItem['description'])->equals($newDescription);
    }

    /**
     * @return array
     */
    private function findList(): array
    {
        return $this->boardFinder->openBoards()[self::BOARD_INDEX]['lists'][self::LIST_INDEX];
    }
}
