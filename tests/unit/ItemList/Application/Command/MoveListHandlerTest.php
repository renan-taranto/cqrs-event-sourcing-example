<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\ItemList\Application\Command;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Application\Command\MoveList;
use Taranto\ListMaker\ItemList\Application\Command\MoveListHandler;
use Taranto\ListMaker\ItemList\Application\Command\RestoreList;
use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class MoveListHandlerTest
 * @package Taranto\ListMaker\Tests\unit\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class MoveListHandlerTest extends Unit
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var MoveListHandler
     */
    private $handler;

    /**
     * @var RestoreList
     */
    private $command;

    /**
     * @var Position
     */
    private $position;

    /**
     * @var BoardId
     */
    private $boardId;

    /**
     * @var ListId
     */
    private $listId;

    /**
     * @var ItemList
     */
    private $list;

    protected function _before()
    {
        $this->listRepository = \Mockery::mock(ListRepository::class);
        $this->handler = new MoveListHandler($this->listRepository);

        $this->listId = ListId::generate();
        $this->position = Position::fromInt(5);
        $this->boardId = BoardId::generate();
        $this->command = new MoveList(
            (string) $this->listId,
            $this->position->toInt(),
            (string) $this->boardId
        );

        $this->list = \Mockery::spy(ItemList::class);
    }

    /**
     * @test
     */
    public function it_moves_the_list(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn($this->list);
        $this->listRepository->shouldReceive('save')->with(isEqual::equalTo($this->list));

        ($this->handler)($this->command);

        $this->list->shouldHaveReceived('move')->with(
            isEqual::equalTo($this->position),
            isEqual::equalTo($this->boardId)
        );
    }

    /**
     * @test
     */
    public function it_throws_when_list_not_found(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn(null);
        $this->listRepository->shouldReceive('save')->never();

        $this->expectExceptionObject(ListNotFound::withListId($this->listId));

        ($this->handler)($this->command);

        $this->list->shouldNotHaveReceived('move');
    }
}
