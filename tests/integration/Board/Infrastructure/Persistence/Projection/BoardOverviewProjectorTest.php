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
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\BoardOverviewFinder;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\BoardOverviewProjector;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class BoardOverviewProjectorTest
 * @package Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardOverviewProjectorTest extends Unit
{
    use BoardEventsProvider;

    private const BOARD_ID = 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392';
    private const CLOSED_BOARD_ID = 'd81805d3-a350-4ef0-81f0-9eb122b4c1ea';

    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var BoardOverviewProjector
     */
    private $projector;

    /**
     * @var BoardOverviewFinder
     */
    private $finder;

    protected function _before(): void
    {
        $this->projector = $this->tester->grabService('test.service_container')->get(BoardOverviewProjector::class);
        $this->finder = $this->tester->grabService('test.service_container')->get(BoardOverviewFinder::class);
    }
    /**
     * @test
     */
    public function it_adds_a_board_overview(): void
    {
        $boardCreated = $this->boardCreatedEvent();

        ($this->projector)($boardCreated);

        $boardOverview = $this->finder->byBoardId((string) $boardCreated->aggregateId());
        expect($boardOverview)
            ->equals([
                'id' => (string) $boardCreated->aggregateId(),
                'title' => (string) $boardCreated->title(),
                'open' => true
            ]);
    }

    /**
     * @test
     */
    public function it_changes_the_board_title_in_the_overview(): void
    {
        $boardTitleChanged = $this->boardTitleChangedEvent(self::BOARD_ID);

        ($this->projector)($boardTitleChanged);

        $boardOverview = $this->finder->byBoardId(self::BOARD_ID);
        expect($boardOverview['title'])->equals((string) $boardTitleChanged->title());
    }

    /**
     * @test
     */
    public function it_marks_a_board_as_closed_in_the_overview(): void
    {
        $boardClosed = $this->boardClosedEvent(self::BOARD_ID);

        ($this->projector)($boardClosed);

        $boardOverview = $this->finder->byBoardId(self::BOARD_ID);
        expect_not($boardOverview['open']);
    }

    /**
     * @test
     */
    public function it_marks_a_board_as_open_in_the_overview(): void
    {
        $boardReopened = $this->boardReopenedEvent(self::CLOSED_BOARD_ID);

        ($this->projector)($boardReopened);

        $boardOverview = $this->finder->byBoardId(self::CLOSED_BOARD_ID);
        expect_that($boardOverview['open']);
    }
}
