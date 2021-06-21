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
use Taranto\ListMaker\Item\Domain\Event\ItemRestored;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\SsePublisher\ItemRestoredPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemRestoredPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemRestoredPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/items';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ItemRestoredPublisher
     */
    private $itemRestoredPublisher;

    /**
     * @var ItemRestored
     */
    private $itemRestoredEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->itemRestoredPublisher = new ItemRestoredPublisher($this->ssePublisher, self::URL);

        $this->itemRestoredEvent = new ItemRestored((string) ItemId::generate());
    }

    /**
     * @test
     */
    public function it_publishes_the_item_restored_event(): void
    {
        ($this->itemRestoredPublisher)($this->itemRestoredEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemRestoredEvent->eventType(),
            'payload' => [
                'id' => (string) $this->itemRestoredEvent->aggregateId()
            ]
        ]));
    }
}
