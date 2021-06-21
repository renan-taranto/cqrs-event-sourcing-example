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
     * @var ItemMovedPublisher
     */
    private $itemMovedPublisher;

    /**
     * @var ItemMoved
     */
    private $itemMovedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->itemMovedPublisher = new ItemMovedPublisher($this->ssePublisher, self::URL);

        $this->itemMovedEvent = new ItemMoved(
            (string) ItemId::generate(),
            2,
            (string) ListId::generate()
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_item_moved_event(): void
    {
        ($this->itemMovedPublisher)($this->itemMovedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemMovedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->itemMovedEvent->aggregateId(),
                'position' => $this->itemMovedEvent->position()->toInt(),
                'listId' => (string) $this->itemMovedEvent->listId()
            ]
        ]));
    }
}
