<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Infrastructure\Persistence\Projection\Board;

use MongoDB\Collection;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Common\ValueObject\Title;
use Taranto\ListMaker\Infrastructure\Persistence\Projection\Board\MongoBoardProjection;
use Taranto\ListMaker\Tests\IntegrationTestCase;

/**
 * Class MongoBoardProjectionTest
 * @package Taranto\ListMaker\Tests\Infrastructure\Persistence\Projection\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MongoBoardProjectionTest extends IntegrationTestCase
{
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
    private $boardId;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var Title
     */
    private $changedTitle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->boardProjection = self::$container->get(MongoBoardProjection::class);
        $this->boardCollection = self::$container->get('mongo.collection.boards');

        $this->boardId = BoardId::generate();
        $this->title = Title::fromString('To-Dos');
        $this->changedTitle = Title::fromString('Tasks');
    }

    /**
     * @test
     */
    public function it_creates_a_board(): void
    {
        $this->boardProjection->createBoard($this->boardId, $this->title);

        $this->assertEquals(
            ['boardId' => (string) $this->boardId, 'title' => (string) $this->title, 'isOpen' => true],
            $this->findBoard((string) $this->boardId)
        );
    }

    /**
     * @test
     */
    public function it_changes_the_board_title(): void
    {
        $this->boardProjection->createBoard($this->boardId, $this->title);

        $this->boardProjection->changeBoardTitle($this->boardId, $this->changedTitle);

        $this->assertEquals(
            ['boardId' => (string) $this->boardId, 'title' => (string) $this->changedTitle, 'isOpen' => true],
            $this->findBoard((string) $this->boardId)
        );
    }

    /**
     * @test
     */
    public function it_closes_the_board(): void
    {
        $this->boardProjection->createBoard($this->boardId, $this->title);

        $this->boardProjection->closeBoard($this->boardId);

        $this->assertEquals(
            ['boardId' => (string) $this->boardId, 'title' => (string) $this->title, 'isOpen' => false],
            $this->findBoard((string) $this->boardId)
        );
    }


    /**
     * @test
     */
    public function it_reopens_the_board(): void
    {
        $this->boardProjection->createBoard($this->boardId, $this->title);
        $this->boardProjection->closeBoard($this->boardId);

        $this->boardProjection->reopenBoard($this->boardId);

        $this->assertEquals(
            ['boardId' => (string) $this->boardId, 'title' => (string) $this->title, 'isOpen' => true],
            $this->findBoard((string) $this->boardId)
        );
    }

    /**
     * @param string $boardId
     * @return array
     */
    private function findBoard(string $boardId): array
    {
        return $this->boardCollection->findOne(
            ['boardId' => (string) $boardId],
            ['projection' => ['_id' => 0], 'typeMap' => ['root' => 'array']]
        );
    }
}
