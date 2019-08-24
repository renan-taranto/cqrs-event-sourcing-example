<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Board\Application\Query;

use Codeception\Specify;
use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Application\Query\BoardById;
use Taranto\ListMaker\Board\Application\Query\BoardByIdHandler;
use Taranto\ListMaker\Board\Application\Query\Data\BoardData;
use Taranto\ListMaker\Board\Application\Query\Data\BoardFinder;
use Taranto\ListMaker\Board\Domain\BoardId;

/**
 * Class BoardByIdHandlerTest
 * @package Taranto\ListMaker\Tests\Board\Application\Query
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardByIdHandlerTest extends Unit
{
    use Specify;

    /**
     * @var string
     */
    private $boardId;

    /**
     * @var BoardData
     */
    private $boardData;

    /**
     * @var BoardFinder
     */
    private $boardFinder;

    /**
     * @var BoardByIdHandler
     */
    private $boardOfIdHandler;

    protected function _before(): void
    {
        $this->boardId = (string) BoardId::generate();
        $this->boardData = new BoardData($this->boardId, 'To-Dos', true);
    }

    /**
     * @test
     */
    public function boardById(): void
    {
        $this->describe('Query Board by Id', function () {
            $this->beforeSpecify(function () {
                $this->boardFinder = \Mockery::mock(BoardFinder::class);
                $this->boardOfIdHandler = new BoardByIdHandler($this->boardFinder);
            });

            $this->should('return a board with the given id', function () {
                $this->boardFinder->shouldReceive('boardById')
                    ->with($this->boardId)
                    ->andReturn($this->boardData);

                $boardData = ($this->boardOfIdHandler)(new BoardById(['boardId' => $this->boardId]));

                expect($boardData)->equals($this->boardData);
            });

            $this->should('return null when board not found', function () {
                $this->boardFinder->shouldReceive('boardById')
                    ->with($this->boardId)
                    ->andReturn(null);

                $boardData = ($this->boardOfIdHandler)(new BoardById(['boardId' => $this->boardId]));

                expect($boardData)->null();
            });
        });
    }
}
