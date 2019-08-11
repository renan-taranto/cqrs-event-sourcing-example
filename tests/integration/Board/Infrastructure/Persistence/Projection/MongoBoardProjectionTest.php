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

use Codeception\Specify;
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
    use Specify;

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

    /**
     * @var BoardId;
     */
    private $newBoardId;

    /**
     * @var Title
     */
    private $newBoardTitle;

    /**
     * @var Title
     */
    private $changedTitle;

    protected function _before(): void
    {
        $this->boardProjection = $this->tester->grabService('test.service_container')->get(MongoBoardProjection::class);
        $this->boardCollection = $this->tester->grabService('test.service_container')->get('mongo.collection.boards');

        $this->newBoardId = BoardId::generate();
        $this->newBoardTitle = Title::fromString('To-Dos');

        $this->changedTitle = Title::fromString('Tasks');
    }

    /**
     * @test
     */
    public function createBoard(): void
    {
        $this->describe("Create Board", function () {
            $this->should("persist a new Board in a MongoDB collection", function () {
                $this->boardProjection->createBoard($this->newBoardId, $this->newBoardTitle);

                $createdBoard = $this->findBoard(['boardId' => (string) $this->newBoardId])[0];
                expect($createdBoard)
                    ->equals([
                        'boardId' => (string) $this->newBoardId,
                        'title' => (string) $this->newBoardTitle,
                        'isOpen' => true
                    ]);
            });
        });
    }

    /**
     * @test
     */
    public function changeBoardTitle(): void
    {
        $this->describe("Change Board Title", function () {
            $this->should("change the title of a Board", function () {
                $board = $this->findBoard()[0];

                $this->boardProjection->changeBoardTitle(
                    BoardId::fromString($board['boardId']),
                    $this->changedTitle
                );

                $updatedBoard = $this->findBoard(['boardId' => $board['boardId']])[0];
                expect($updatedBoard['title'])->equals((string) $this->changedTitle);
            });
        });
    }

    /**
     * @test
     */
    public function closeBoard(): void
    {
        $this->describe("Close Board", function () {
            $this->should("close a Board", function () {
                $board = $this->findBoard(['isOpen' => true])[0];

                $this->boardProjection->closeBoard(BoardId::fromString($board['boardId']));

                $updatedBoard = $this->findBoard(['boardId' => $board['boardId']])[0];
                expect_not($updatedBoard['isOpen']);
            });
        });
    }

    /**
     * @test
     */
    public function reopenBoard(): void
    {
        $this->describe("Reopen Board", function () {
            $this->should("reopen a Board", function () {
                $board = $this->findBoard(['isOpen' => false])[0];

                $this->boardProjection->reopenBoard(BoardId::fromString($board['boardId']));

                $updatedBoard = $this->findBoard(['boardId' => $board['boardId']])[0];
                expect_that($updatedBoard['isOpen']);
            });
        });
    }

    /**
     * @param array $filter
     * @return array
     */
    private function findBoard(array $filter = []): array
    {
        return $this->boardCollection->find(
            $filter,
            ['projection' => ['_id' => 0], 'typeMap' => ['root' => 'array']]
        )->toArray();
    }
}
