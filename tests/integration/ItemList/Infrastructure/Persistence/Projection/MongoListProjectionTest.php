<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\ItemList\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Domain\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection\MongoListProjection;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class MongoListProjectionTest
 * @package Taranto\ListMaker\Tests\integration\Board\ItemList\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MongoListProjectionTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var MongoListProjection
     */
    private $listProjection;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    protected function _before(): void
    {
        $this->listProjection = $this->tester->grabService('test.service_container')->get(MongoListProjection::class);
        $this->boardFinder = $this->tester->grabService('test.service_container')->get(BoardFinder::class);
    }

    /**
     * @test
     */
    public function it_creates_a_list(): void
    {
        $boardId = $this->boardFinder->openBoards()[0]['boardId'];
        $listId = ListId::generate();
        $title = Title::fromString('Doing');

        $this->listProjection->createList($listId, $title, BoardId::fromString($boardId));

        $list = end($this->boardFinder->byId($boardId)['lists']);
        expect($list)->equals(['id' => (string) $listId, 'title' => (string) $title, 'items' => []]);
    }

    /**
     * @test
     */
    public function it_archives_a_list(): void
    {
        $lists = $this->boardFinder->openBoards()[0]['lists'];
        $listToBeArchived = $lists[0];

        $this->listProjection->archiveList(ListId::fromString($listToBeArchived['id']));

        $archivedLists = $this->boardFinder->openBoards()[0]['archivedLists'];
        expect(end($archivedLists))->equals($listToBeArchived);
    }

    /**
     * @test
     */
    public function it_restores_a_list(): void
    {
        $archivedLists = $this->boardFinder->openBoards()[0]['archivedLists'];
        $listToBeRestored = $archivedLists[0];

        $this->listProjection->restoreList(ListId::fromString($listToBeRestored['id']));

        $lists = $this->boardFinder->openBoards()[0]['lists'];
        expect(end($lists))->equals($listToBeRestored);
    }

    /**
     * @test
     */
    public function it_reorders_a_list(): void
    {
        $lists = $this->boardFinder->openBoards()[0]['lists'];
        $listToBeReordered = $lists[2];
        $toPosition = 1;

        $this->listProjection->reorderList(ListId::fromString($listToBeReordered['id']), $toPosition);

        $lists = $this->boardFinder->openBoards()[0]['lists'];
        expect($lists[$toPosition])->equals($listToBeReordered);
    }
}
