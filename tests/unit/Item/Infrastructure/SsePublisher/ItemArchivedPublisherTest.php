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
use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Item\Domain\ItemId;
use Taranto\ListMaker\Item\Infrastructure\SsePublisher\ItemArchivedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemArchivedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemArchivedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/items';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ItemArchivedPublisher
     */
    private $itemArchivedPublisher;

    /**
     * @var ItemArchived
     */
    private $itemArchivedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->itemArchivedPublisher = new ItemArchivedPublisher($this->ssePublisher, self::URL);

        $this->itemArchivedEvent = new ItemArchived((string) ItemId::generate());
    }

    /**
     * @test
     */
    public function it_publishes_the_item_archived_event(): void
    {
        ($this->itemArchivedPublisher)($this->itemArchivedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->itemArchivedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->itemArchivedEvent->aggregateId()
            ]
        ]));
    }
}
