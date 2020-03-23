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
use MongoDB\Collection;
use Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection\ListProjector;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class ListProjectorTest
 * @package Taranto\ListMaker\Tests\Integration\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListProjectorTest extends Unit
{
    use ListEventsProvider;

    private const BOARD_ID = 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392';
    private const LIST_ID = '197c76a8-dcd9-473e-afd8-3ea6556484f3';
    private const ARCHIVED_LIST_ID = 'd33a1a8e-5933-4fbc-b60c-0f37d201b2b4';

    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var ListProjector
     */
    private $projector;

    /**
     * @var Collection
     */
    private $boardsCollection;

    protected function _before(): void
    {
        $this->projector = $this->tester->grabService('test.service_container')->get(ListProjector::class);
        $this->boardsCollection = $this->tester->grabService('mongo.collection.boards');
    }

    /**
     * @test
     */
    public function it_creates_a_list(): void
    {
        $listCreated = $this->listCreatedEvent(self::BOARD_ID);

        ($this->projector)($listCreated);

        $list = $this->listById((string) $listCreated->aggregateId());
        expect($list)->equals([
            'id' => (string) $listCreated->aggregateId(),
            'title' => (string) $listCreated->title(),
            'items' => [],
            'archivedItems' => []
        ]);
    }

    /**
     * @test
     */
    public function it_archives_a_list(): void
    {
        $list = $this->listById(self::LIST_ID);
        $listArchived = $this->listArchivedEvent(self::LIST_ID);

        ($this->projector)($listArchived);

        $archivedList = $this->archivedListById(self::LIST_ID);
        expect($archivedList)->equals($list);
    }

    /**
     * @test
     */
    public function it_restores_a_list(): void
    {
        $archivedList = $this->archivedListById(self::ARCHIVED_LIST_ID);
        $listRestored = $this->listRestoredEvent(self::ARCHIVED_LIST_ID);

        ($this->projector)($listRestored);

        $list = $this->listById(self::ARCHIVED_LIST_ID);
        expect($list)->equals($archivedList);
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_a_list(): void
    {
        $listTitleChanged = $this->listTitleChangedEvent(self::LIST_ID);

        ($this->projector)($listTitleChanged);

        $list = $this->listById(self::LIST_ID);
        expect($list['title'])->equals((string) $listTitleChanged->title());
    }

    /**
     * @test
     */
    public function it_moves_the_list_in_the_same_board(): void
    {
        $list = $this->listById(self::LIST_ID);
        $updatedPosition = 1;
        $listMoved = $this->listMovedEvent(self::LIST_ID, $updatedPosition, self::BOARD_ID);

        ($this->projector)($listMoved);

        $updatedList = $this->listByBoardIdAndPosition(self::BOARD_ID, $updatedPosition);
        expect($updatedList)->equals($list);
    }

    /**
     * @test
     */
    public function it_moves_the_list_to_another_board(): void
    {
        $boardId = '4b2baa7e-315b-41cc-857b-8852619d230b';
        $list = $this->listById(self::LIST_ID);
        $updatedPosition = 0;
        $listMoved = $this->listMovedEvent(self::LIST_ID, $updatedPosition, $boardId);

        ($this->projector)($listMoved);

        $updatedList = $this->listByBoardIdAndPosition($boardId, $updatedPosition);
        expect($updatedList)->equals($list);
    }

    /**
     * @param string $listId
     * @return array
     */
    private function listById(string $listId): array
    {
        return $this->boardsCollection->findOne(
            ['lists.id' => $listId],
            ['projection' => ['lists.$' => true, '_id' => false]]
        )['lists'][0];
    }

    /**
     * @param string $listId
     * @return array
     */
    private function archivedListById(string $listId): array
    {
        return $this->boardsCollection->findOne(
            ['archivedLists.id' => $listId],
            ['projection' => ['archivedLists.$' => true, '_id' => false]]
        )['archivedLists'][0];
    }

    /**
     * @param string $boardId
     * @param int $index
     * @return array
     */
    private function listByBoardIdAndPosition(string $boardId, int $index): array
    {
        return $this->boardsCollection->findOne(
            ['id' => $boardId],
            ['projection' => ['lists' => true, '_id' => false]]
        )['lists'][$index];
    }
}
