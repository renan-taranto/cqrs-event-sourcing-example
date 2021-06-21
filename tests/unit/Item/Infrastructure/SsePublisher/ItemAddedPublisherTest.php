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
use Taranto\ListMaker\Item\Domain\Event\ItemAdded;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\SsePublisher\ItemAddedPublisher;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemAddedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemAddedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/items';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ItemAddedPublisher
     */
    private $itemAddedPublisher;

    /**
     * @var ItemAdded
     */
    private $itemAddedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->itemAddedPublisher = new ItemAddedPublisher($this->ssePublisher, self::URL);

        $this->itemAddedEvent = new ItemAdded(
            (string) ItemId::generate(),
            'Task A',
            3,
            (string) ListId::generate()
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_item_added_event(): void
    {
        ($this->itemAddedPublisher)($this->itemAddedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemAddedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->itemAddedEvent->aggregateId(),
                'title' => (string) $this->itemAddedEvent->title(),
                'position' => $this->itemAddedEvent->position()->toInt(),
                'listId' => (string) $this->itemAddedEvent->listId()
            ]
        ]));
    }
}
