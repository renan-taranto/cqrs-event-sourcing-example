<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\ItemList\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Domain\Board;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\BoardRepository;
use Taranto\ListMaker\Board\Domain\Exception\BoardNotFound;
use Taranto\ListMaker\ItemList\Application\Command\CreateList;
use Taranto\ListMaker\ItemList\Application\Command\CreateListHandler;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

/**
 * Class CreateListHandlerTest
 * @package Taranto\ListMaker\Tests\unit\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class CreateListHandlerTest extends Unit
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var BoardRepository
     */
    private $boardRepository;

    /**
     * @var CreateListHandler
     */
    private $handler;

    /**
     * @var CreateList
     */
    private $command;

    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var ItemList
     */
    private $list;

    protected function _before()
    {
        $this->listRepository = \Mockery::spy(ListRepository::class);
        $this->boardRepository = \Mockery::mock(BoardRepository::class);
        $this->handler = new CreateListHandler($this->listRepository, $this->boardRepository);

        $listId = ListId::generate();
        $this->boardId = BoardId::generate();
        $title = Title::fromString('Backlog');
        $this->command = CreateList::request(
            (string) $listId,
            ['title' => (string) $title, 'boardId' => (string) $this->boardId]
        );

        $this->board = \Mockery::mock(Board::class);
        $this->list = ItemList::create($listId, $title, $this->boardId);
    }

    /**
     * @test
     */
    public function it_creates_a_list(): void
    {
        $this->boardRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->boardId))
            ->andReturn($this->board);

        ($this->handler)($this->command);

        $this->listRepository->shouldHaveReceived('save')->with(isEqual::equalTo($this->list));
    }

    /**
     * @test
     */
    public function it_throws_when_board_not_found(): void
    {
        $this->boardRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->boardId))
            ->andReturnNull();

        $this->expectExceptionObject(BoardNotFound::withBoardId($this->boardId));

        ($this->handler)($this->command);
    }
}
