<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\ItemList\Application\Query\ListFinder;
use Taranto\ListMaker\ItemList\Domain\Event\ListMoved;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\SsePublisher\ListMovedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListMovedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListMovedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/lists';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var array
     */
    private $list;

    /**
     * @var ListFinder
     */
    private $listFinder;

    /**
     * @var ListMovedPublisher
     */
    private $listMovedPublisher;

    /**
     * @var ListMoved
     */
    private $listMovedEvent;

    protected function _before(): void
    {
        $this->list = [
            'id' => (string) ListId::generate(),
            'title' => 'To Do',
            'items' => [
                [
                    "id" => "c8f94b93-a41d-490d-85e0-47990bc4792f",
                    "title" => "Feature: Items",
                    "description" => "In order to add tasks to be done..."
                ]
            ],
            'archivedItems' => [
                [
                    "id" => "a7bb5c80-0b83-41f2-83cc-b1477a298434",
                    "title" => "Update: Improve mongo queries performance",
                    "description" => "In order to have faster results..."
                ]
            ]
        ];

        $this->listFinder = \Mockery::mock(ListFinder::class);
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);

        $this->listMovedPublisher = new ListMovedPublisher($this->listFinder, $this->ssePublisher, self::URL);

        $this->listMovedEvent = new ListMoved(
            $this->list['id'],
            3,
            (string) BoardId::generate()
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_list_moved_event(): void
    {
        $this->listFinder->shouldReceive('byId')
            ->with(isEqual::equalTo($this->list['id']))
            ->andReturn($this->list);

        ($this->listMovedPublisher)($this->listMovedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->listMovedEvent->eventType(),
            'payload' => [
                'id' => $this->list['id'],
                'title' => $this->list['title'],
                'items' => $this->list['items'],
                'archivedItems' => $this->list['archivedItems'],
                'position' => $this->listMovedEvent->position()->toInt(),
                'boardId' => (string) $this->listMovedEvent->boardId()
            ]
        ]));
    }
}
