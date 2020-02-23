<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Board\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use MongoDB\Collection;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\MongoBoardProjection;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class MongoBoardProjectionTest
 * @package Taranto\ListMaker\Tests\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MongoBoardProjectionTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var MongoBoardProjection
     */
    private $boardProjection;

    /**
     * @var Collection
     */
    private $boardCollection;

    protected function _before(): void
    {
        $this->boardProjection = $this->tester->grabService('test.service_container')->get(MongoBoardProjection::class);
        $this->boardCollection = $this->tester->grabService('test.service_container')->get('mongo.collection.boards');
    }

    /**
     * @test
     */
    public function it_adds_a_board_to_the_collection(): void
    {
        $boardId = BoardId::generate();
        $title = Title::fromString('To-Dos');
        $this->boardProjection->createBoard($boardId, $title);

        $createdBoard = $this->findBoards(['id' => (string) $boardId])[0];
        expect($createdBoard)
            ->equals([
                'id' => (string) $boardId,
                'title' => (string) $title,
                'open' => true,
                'lists' => [],
                'archivedLists' => []
            ]);
    }

    /**
     * @test
     */
    public function it_changes_the_title_of_a_board(): void
    {
        $board = $this->findBoards()[0];
        $changedTitle = Title::fromString('Tasks');

        $this->boardProjection->changeBoardTitle(
            BoardId::fromString($board['id']),
            $changedTitle
        );

        $updatedBoard = $this->findBoards(['id' => $board['id']])[0];
        expect($updatedBoard['title'])->equals((string) $changedTitle);
    }

    /**
     * @test
     */
    public function it_closes_a_board(): void
    {
        $board = $this->findBoards(['open' => true])[0];

        $this->boardProjection->closeBoard(BoardId::fromString($board['id']));

        $updatedBoard = $this->findBoards(['id' => $board['id']])[0];
        expect_not($updatedBoard['open']);
    }

    /**
     * @test
     */
    public function it_reopens_a_board(): void
    {
        $board = $this->findBoards(['open' => false])[0];

        $this->boardProjection->reopenBoard(BoardId::fromString($board['id']));

        $updatedBoard = $this->findBoards(['id' => $board['id']])[0];
        expect_that($updatedBoard['open']);
    }

    /**
     * @param array $filter
     * @return array
     */
    private function findBoards(array $filter = []): array
    {
        return $this->boardCollection->find(
            $filter,
            ['projection' => ['_id' => 0], 'typeMap' => ['root' => 'array']]
        )->toArray();
    }
}
