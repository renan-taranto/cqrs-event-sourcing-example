<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Application\Query\Finder\BoardFinder;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\BoardProjector;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class BoardProjectorTest
 * @package Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardProjectorTest extends Unit
{
    use BoardEventsProvider;

    private const BOARD_ID = 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392';
    private const CLOSED_BOARD_ID = 'd81805d3-a350-4ef0-81f0-9eb122b4c1ea';

    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var BoardProjector
     */
    private $projector;

    /**
     * @var BoardFinder
     */
    private $finder;

    protected function _before(): void
    {
        $this->projector = $this->tester->grabService('test.service_container')->get(BoardProjector::class);
        $this->finder = $this->tester->grabService('test.service_container')->get(BoardFinder::class);
    }

    /**
     * @test
     */
    public function it_adds_a_board(): void
    {
        $boardCreated = $this->boardCreatedEvent();
        ($this->projector)($boardCreated);

        $board = $this->finder->byId((string) $boardCreated->aggregateId());
        expect($board)
            ->equals([
                'id' => (string) $boardCreated->aggregateId(),
                'title' => (string) $boardCreated->title(),
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
        $boardTitleChanged = $this->boardTitleChangedEvent(self::BOARD_ID);

        ($this->projector)($boardTitleChanged);

        $board = $this->finder->byId(self::BOARD_ID);
        expect($board['title'])->equals((string) $boardTitleChanged->title());
    }

    /**
     * @test
     */
    public function it_marks_a_board_as_closed(): void
    {
        $boardClosed = $this->boardClosedEvent(self::BOARD_ID);

        ($this->projector)($boardClosed);;

        $board = $this->finder->byId(self::BOARD_ID);
        expect_not($board['open']);
    }

    /**
     * @test
     */
    public function it_marks_a_board_as_open(): void
    {
        $boardReopened = $this->boardReopenedEvent(self::CLOSED_BOARD_ID);

        ($this->projector)($boardReopened);

        $boardOverview = $this->finder->byId(self::CLOSED_BOARD_ID);
        expect_that($boardOverview['open']);
    }
}
