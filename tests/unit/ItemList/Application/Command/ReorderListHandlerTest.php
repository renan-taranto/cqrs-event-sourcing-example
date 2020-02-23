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
use Taranto\ListMaker\ItemList\Application\Command\ReorderList;
use Taranto\ListMaker\ItemList\Application\Command\ReorderListHandler;
use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Position;

/**
 * Class ReorderListHandlerTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Application\Command
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ReorderListHandlerTest extends Unit
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var ReorderListHandler
     */
    private $handler;

    /**
     * @var ReorderList
     */
    private $command;

    /**
     * @var Position
     */
    private $toPosition;

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
        $this->handler = new ReorderListHandler($this->listRepository);

        $this->listId = ListId::generate();
        $this->toPosition = Position::fromInt(2);
        $this->command = ReorderList::request((string) $this->listId, ['toPosition' => $this->toPosition->toInt()]);

        $this->list = \Mockery::spy(ItemList::class);
    }

    /**
     * @test
     */
    public function it_reorders_the_list(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn($this->list);
        $this->listRepository->shouldReceive('save')->with(isEqual::equalTo($this->list));

        ($this->handler)($this->command);

        $this->list->shouldHaveReceived('reorder')->with(isEqual::equalTo($this->toPosition));
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
    }
}