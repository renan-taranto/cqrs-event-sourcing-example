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
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class BoardFinderTest
 * @package Taranto\ListMaker\Tests\Integration\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardFinderTest extends Unit
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    protected function _before(): void
    {
        $this->boardFinder = $this->tester->grabService('test.service_container')->get(BoardFinder::class);
    }

    /**
     * @test
     */
    public function it_returns_open_boards(): void
    {
        $openBoards = $this->boardFinder->openBoards();

        foreach ($openBoards as $board) {
            expect_that($board['open']);
        }
    }

    /**
     * @test
     */
    public function it_returns_closed_boards(): void
    {
        $closedBoards = $this->boardFinder->closedBoards();

        foreach ($closedBoards as $board) {
            expect_not($board['open']);
        }
    }

    /**
     * @test
     */
    public function it_returns_a_board_with_the_given_id(): void
    {
        $boardId = 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392';
        $board = $this->boardFinder->byId($boardId);

        expect($board['id'])->equals($boardId);
    }

    /**
     * @test
     */
    public function it_returns_null_when_board_not_found(): void
    {
        expect($this->boardFinder->byId('12345'))->null();
    }
}
