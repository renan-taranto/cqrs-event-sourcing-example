<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher;

use Codeception\Test\Unit;
use Hamcrest\Core\IsEqual;
use Taranto\ListMaker\Item\Application\Query\ItemFinder;
use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\SsePublisher\ItemMovedPublisher;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemMovedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemMovedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/items';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var array
     */
    private $item;

    /**
     * @var ItemFinder
     */
    private $itemFinder;

    /**
     * @var ItemMovedPublisher
     */
    private $itemMovedPublisher;

    /**
     * @var ItemMoved
     */
    private $itemMovedEvent;

    protected function _before()
    {
        $this->item = [
            'id' => (string) ItemId::generate(),
            'title' => 'Feature: SSE',
            'description' => 'In order to send...'
        ];

        $this->itemFinder = \Mockery::mock(ItemFinder::class);
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);

        $this->itemMovedPublisher = new ItemMovedPublisher(
            $this->itemFinder,
            $this->ssePublisher,
            self::URL
        );

        $this->itemMovedEvent = new ItemMoved(
            $this->item['id'],
            2,
            (string) ListId::generate()
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_item_moved_event(): void
    {
        $this->itemFinder->shouldReceive('byId')
            ->with(isEqual::equalTo($this->item['id']))
            ->andReturn($this->item);

        ($this->itemMovedPublisher)($this->itemMovedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemMovedEvent->eventType(),
            'payload' => [
                'id' => $this->item['id'],
                'title' => $this->item['title'],
                'description' => $this->item['description'],
                'position' => $this->itemMovedEvent->position()->toInt(),
                'listId' => (string) $this->itemMovedEvent->listId()
            ]
        ]));
    }
}
