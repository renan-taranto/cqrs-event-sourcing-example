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
use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\SsePublisher\ItemDescriptionChangedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemDescriptionChangedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemDescriptionChangedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/items';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ItemDescriptionChangedPublisher
     */
    private $itemDescriptionChangedPublisher;

    /**
     * @var ItemDescriptionChanged
     */
    private $itemDescriptionChangedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->itemDescriptionChangedPublisher = new ItemDescriptionChangedPublisher($this->ssePublisher, self::URL);

        $this->itemDescriptionChangedEvent = new ItemDescriptionChanged(
            (string) ItemId::generate(),
            'In order to publish...'
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_item_description_changed_event(): void
    {
        ($this->itemDescriptionChangedPublisher)($this->itemDescriptionChangedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemDescriptionChangedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->itemDescriptionChangedEvent->aggregateId(),
                'description' => (string) $this->itemDescriptionChangedEvent->description()
            ]
        ]));
    }
}
