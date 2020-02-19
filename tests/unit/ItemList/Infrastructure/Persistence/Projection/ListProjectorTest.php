<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\unit\ItemList\Infrastructure\Persistence\Projection;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\Event\ListReordered;
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection\ListProjection;
use Taranto\ListMaker\ItemList\Infrastructure\Persistence\Projection\ListProjector;

/**
 * Class ListProjectorTest
 * @package Taranto\ListMaker\Tests\unit\ItemList\Infrastructure\Persistence\Projection
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListProjectorTest extends Unit
{
    /**
     * @var ListProjection
     */
    private $projection;

    /**
     * @var ListProjector
     */
    private $projector;

    /**
     * @var ListCreated
     */
    private $listCreated;

    /**
     * @var ListTitleChanged
     */
    private $listTitleChanged;

    /**
     * @var ListArchived
     */
    private $listArchived;

    /**
     * @var ListRestored
     */
    private $listRestored;

    /**
     * @var ListReordered
     */
    private $listReordered;

    protected function _before()
    {
        $this->projection = \Mockery::spy(ListProjection::class);
        $this->projector = new ListProjector($this->projection);

        $listId = (string) ListId::generate();
        $boardId = (string) BoardId::generate();
        $this->listCreated = ListCreated::occur($listId, ['title' => 'To Do', 'boardId' => $boardId]);
        $this->listTitleChanged = ListTitleChanged::occur($listId, ['title' => 'Doing']);
        $this->listArchived = ListArchived::occur($listId);
        $this->listRestored = ListRestored::occur($listId);
        $this->listReordered = ListReordered::occur($listId, ['toPosition' => 2]);
    }

    /**
     * @test
     */
    public function it_projects_the_ListCreated_event(): void
    {
        ($this->projector)($this->listCreated);
        $this->projection->shouldHaveReceived('createList')
            ->with(
                isEqual::equalTo($this->listCreated->aggregateId()),
                isEqual::equalTo($this->listCreated->title()),
                isEqual::equalTo($this->listCreated->boardId())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_ListTitleChanged_event(): void
    {
        ($this->projector)($this->listTitleChanged);
        $this->projection->shouldHaveReceived('changeListTitle')
            ->with(
                isEqual::equalTo($this->listTitleChanged->aggregateId()),
                isEqual::equalTo($this->listTitleChanged->title())
            );
    }

    /**
     * @test
     */
    public function it_projects_the_ListArchived_event(): void
    {
        ($this->projector)($this->listArchived);
        $this->projection->shouldHaveReceived('archiveList')
            ->with(isEqual::equalTo($this->listTitleChanged->aggregateId()));
    }

    /**
     * @test
     */
    public function it_projects_the_ListRestored_event(): void
    {
        ($this->projector)($this->listRestored);
        $this->projection->shouldHaveReceived('restoreList')
            ->with(isEqual::equalTo($this->listRestored->aggregateId()));
    }

    /**
     * @test
     */
    public function it_projects_the_ListReordered_event(): void
    {
        ($this->projector)($this->listReordered);
        $this->projection->shouldHaveReceived('reorderList')
            ->with(
                isEqual::equalTo($this->listReordered->aggregateId()),
                isEqual::equalTo($this->listReordered->toPosition())
            );
    }
}
