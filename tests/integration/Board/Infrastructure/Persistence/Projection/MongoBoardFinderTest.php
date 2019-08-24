<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\integration\Board\Infrastructure\Persistence\Projection;

use Codeception\Specify;
use Codeception\Test\Unit;
use MongoDB\Collection;
use Taranto\ListMaker\Board\Application\Query\Data\BoardFinder;
use Taranto\ListMaker\Board\Infrastructure\Persistence\Projection\MongoBoardFinder;
use Taranto\ListMaker\Tests\IntegrationTester;

/**
 * Class MongoBoardFinderTest
 * @package Taranto\ListMaker\Tests\integration\Board\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MongoBoardFinderTest extends Unit
{
    use Specify;

    /**
     * @var IntegrationTester
     */
    protected $tester;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * @var Collection
     */
    private $boardCollection;

    protected function _before(): void
    {
        $this->boardFinder = $this->tester->grabService('test.service_container')->get(MongoBoardFinder::class);
        $this->boardCollection = $this->tester->grabService('test.service_container')->get('mongo.collection.boards');
    }

    /**
     * @test
     */
    public function findOpenBoards(): void
    {
        $this->describe('Find open boards', function() {
            $this->should('return open boards', function() {
                $openBoards = $this->boardFinder->openBoards();

                foreach ($openBoards as $board) {
                    expect_that($board->isOpen());
                }
            });
        });
    }

    /**
     * @test
     */
    public function findClosedBoards(): void
    {
        $this->describe('Find closed boards', function() {
            $this->should('return closed boards', function() {
                $closedBoards = $this->boardFinder->closedBoards();

                foreach ($closedBoards as $board) {
                    expect_not($board->isOpen());
                }
            });
        });
    }

    /**
     * @test
     */
    public function findBoardById(): void
    {
        $this->describe('Find board by id', function() {
            $this->should('return a board with the given id', function() {
                $boardId = 'b6e7cfd0-ae2b-44ee-9353-3e5d95e57392';
                $board = $this->boardFinder->boardById($boardId);

                expect($board->getBoardId())->equals($boardId);
            });

            $this->should('return null when board is not found', function() {
                expect($this->boardFinder->boardById('12345'))->null();
            });
        });
    }
}
