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
use Taranto\ListMaker\ItemList\Application\Command\ChangeListTitle;
use Taranto\ListMaker\ItemList\Application\Command\ChangeListTitleHandler;
use Taranto\ListMaker\ItemList\Domain\Exception\ListNotFound;
use Taranto\ListMaker\ItemList\Domain\ItemList;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Domain\ListRepository;
use Taranto\ListMaker\Shared\Domain\ValueObject\Title;

class ChangeListTitleHandlerTest extends Unit
{
    /**
     * @var ListRepository
     */
    private $listRepository;

    /**
     * @var ChangeListTitleHandler
     */
    private $handler;

    /**
     * @var ChangeListTitle
     */
    private $command;

    /**
     * @var ListId
     */
    private $listId;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var ItemList
     */
    private $list;

    protected function _before()
    {
        $this->listRepository = \Mockery::mock(ListRepository::class);
        $this->handler = new ChangeListTitleHandler($this->listRepository);

        $this->listId = ListId::generate();
        $this->title = Title::fromString('Backlog');
        $this->command = ChangeListTitle::request(
            (string) $this->listId,
            ['title' => (string) $this->title]
        );

        $this->list = \Mockery::spy(ItemList::class);
    }

    /**
     * @test
     */
    public function it_changes_the_list_title(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturn($this->list);
        $this->listRepository->shouldReceive('save')->with(isEqual::equalTo($this->list));

        ($this->handler)($this->command);

        $this->list->shouldHaveReceived('changeTitle')->with(isEqual::equalTo((string) $this->title));
    }

    /**
     * @test
     */
    public function it_throws_when_list_not_found(): void
    {
        $this->listRepository->shouldReceive('get')
            ->with(isEqual::equalTo($this->listId))
            ->andReturnNull();
        $this->listRepository->shouldReceive('save')->never();

        $this->expectExceptionObject(ListNotFound::withListId($this->listId));

        ($this->handler)($this->command);
    }
}
