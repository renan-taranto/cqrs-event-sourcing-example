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

use PHPUnit\Framework\TestCase;
use Taranto\ListMaker\Domain\Model\Board\BoardId;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardClosed;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardCreated;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardReopened;
use Taranto\ListMaker\Domain\Model\Board\Event\BoardTitleChanged;
use Taranto\ListMaker\Infrastructure\Persistence\Projection\Board\BoardProjection;
use Taranto\ListMaker\Infrastructure\Persistence\Projection\Board\BoardProjector;

/**
 * Class BoardProjectorTest
 * @package Taranto\ListMaker\Tests\Infrastructure\Persistence\Projection\Board
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardProjectorTest extends TestCase
{
    /**
     * @var BoardProjection
     */
    private $boardProjection;

    /**
     * @var BoardCreated
     */
    private $boardCreated;

    /**
     * @var BoardTitleChanged
     */
    private $boardTitleChanged;

    /**
     * @var BoardClosed
     */
    private $boardClosed;

    /**
     * @var BoardReopened
     */
    private $boardReopened;

    protected function setUp(): void
    {
        $this->boardProjection = $this->prophesize(BoardProjection::class);

        $aggregateId = BoardId::generate();
        $this->boardCreated = BoardCreated::occur((string) $aggregateId, ['title' => 'To-Dos']);
        $this->boardTitleChanged = BoardTitleChanged::occur((string) $aggregateId, ['title' => 'To-Dos']);
        $this->boardClosed = BoardClosed::occur((string) $aggregateId);
        $this->boardReopened = BoardReopened::occur((string) $aggregateId);
    }

    /**
     * @test
     */
    public function it_projects_the_board_created_event(): void
    {
        $this->boardProjection
            ->createBoard($this->boardCreated->aggregateId(), $this->boardCreated->title())
            ->shouldBeCalled();
        $boardProjector = new BoardProjector($this->boardProjection->reveal());

        ($boardProjector)($this->boardCreated);
    }

    /**
     * @test
     */
    public function it_projects_the_board_title_changed_event(): void
    {
        $this->boardProjection
            ->changeBoardTitle($this->boardTitleChanged->aggregateId(), $this->boardTitleChanged->title())
            ->shouldBeCalled();
        $boardProjector = new BoardProjector($this->boardProjection->reveal());

        ($boardProjector)($this->boardTitleChanged);
    }

    /**
     * @test
     */
    public function it_projects_the_board_closed_event(): void
    {
        $this->boardProjection->closeBoard($this->boardClosed->aggregateId())->shouldBeCalled();
        $boardProjector = new BoardProjector($this->boardProjection->reveal());

        ($boardProjector)($this->boardClosed);
    }

    /**
     * @test
     */
    public function it_projects_the_board_reopened_event(): void
    {
        $this->boardProjection->reopenBoard($this->boardReopened->aggregateId())->shouldBeCalled();
        $boardProjector = new BoardProjector($this->boardProjection->reveal());

        ($boardProjector)($this->boardReopened);
    }
}
