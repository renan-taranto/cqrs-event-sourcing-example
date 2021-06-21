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
use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\SsePublisher\ItemTitleChangedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemTitleChangedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemTitleChangedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/items';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ItemTitleChangedPublisher
     */
    private $itemTitleChangedPublisher;

    /**
     * @var ItemTitleChanged
     */
    private $itemTitleChangedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->itemTitleChangedPublisher = new ItemTitleChangedPublisher($this->ssePublisher, self::URL);

        $this->itemTitleChangedEvent = new ItemTitleChanged(
            (string) ItemId::generate(),
            'Item B'
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_item_title_changed_event(): void
    {
        ($this->itemTitleChangedPublisher)($this->itemTitleChangedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemTitleChangedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->itemTitleChangedEvent->aggregateId(),
                'title' => (string) $this->itemTitleChangedEvent->title()
            ]
        ]));
    }
}
