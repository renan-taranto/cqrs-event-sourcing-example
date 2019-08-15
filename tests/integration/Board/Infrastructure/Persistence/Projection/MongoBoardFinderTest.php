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
use Taranto\ListMaker\Board\Domain\BoardFinder;
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
                $openBoards = $this->boardCollection->find(['isOpen' => true])->toArray();

                $boardsFound = $this->boardFinder->openBoards();

                expect($boardsFound)->equals($openBoards);
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
                $closedBoards = $this->boardCollection->find(['isOpen' => false])->toArray();

                $boardsFound = $this->boardFinder->closedBoards();

                expect($boardsFound)->equals($closedBoards);
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
                $board = $this->boardCollection->findOne([], ['typeMap' => ['root' => 'array', 'document' => 'array']]);

                $boardFound = $this->boardFinder->boardById($board['boardId']);

                expect($boardFound)->equals($board);
            });

            $this->should('return null when board is not found', function() {
                expect($this->boardFinder->boardById('12345'))->null();
            });
        });
    }
}
