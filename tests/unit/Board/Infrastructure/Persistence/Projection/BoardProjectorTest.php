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
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Board\Domain\Event\BoardCreated;
use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Board\Domain\Event\BoardTitleChanged;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\BoardProjection;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\BoardProjector;

/**
 * Class BoardProjectorTest
 * @package Taranto\ListMaker\Tests\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardProjectorTest extends Unit
{
    /**
     * @var BoardProjection
     */
    private $projection;

    /**
     * @var BoardProjector
     */
    private $projector;

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

    protected function _before(): void
    {
        $this->projection = \Mockery::spy(BoardProjection::class);
        $this->projector = new BoardProjector($this->projection);

        $aggregateId = BoardId::generate();
        $this->boardCreated = BoardCreated::occur((string) $aggregateId, ['title' => 'To-Dos']);
        $this->boardTitleChanged = BoardTitleChanged::occur((string) $aggregateId, ['title' => 'Tasks']);
        $this->boardClosed = BoardClosed::occur((string) $aggregateId);
        $this->boardReopened = BoardReopened::occur((string) $aggregateId);
    }

    /**
     * @test
     */
    public function it_projects_the_BoardCreated_event(): void
    {
        ($this->projector)($this->boardCreated);

        $this->projection->shouldHaveReceived('createBoard')
            ->with(
                isEqual::equalTo($this->boardCreated->aggregateId()),
                isEqual::equalTo($this->boardCreated->title())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_BoardTitleChanged_event(): void
    {
        ($this->projector)($this->boardTitleChanged);

        $this->projection->shouldHaveReceived('changeBoardTitle')
            ->with(
                isEqual::equalTo($this->boardTitleChanged->aggregateId()),
                isEqual::equalTo($this->boardTitleChanged->title())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_BoardClosed_event(): void
    {
        ($this->projector)($this->boardClosed);

        $this->projection->shouldHaveReceived('closeBoard')
            ->with(isEqual::equalTo($this->boardClosed->aggregateId()));
    }

    /**
     * @test
     */
    public function it_projects_the_BoardReopened_event(): void
    {
        ($this->projector)($this->boardReopened);

        $this->projection->shouldHaveReceived('reopenBoard')
            ->with(isEqual::equalTo($this->boardReopened->aggregateId()));
    }
}
