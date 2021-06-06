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
use Taranto\ListMaker\ItemList\Domain\Event\ListRestored;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\SsePublisher\ListRestoredPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListRestoredPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListRestoredPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/lists';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ListRestoredPublisher
     */
    private $listRestoredPublisher;

    /**
     * @var ListRestored
     */
    private $listRestoredEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->listRestoredPublisher = new ListRestoredPublisher($this->ssePublisher, self::URL);

        $this->listRestoredEvent = new ListRestored((string) ListId::generate());
    }

    /**
     * @test
     */
    public function it_publishes_the_list_restored_event(): void
    {
        ($this->listRestoredPublisher)($this->listRestoredEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->listRestoredEvent->eventType(),
            'payload' => [
                'id' => (string) $this->listRestoredEvent->aggregateId()
            ]
        ]));
    }
}
