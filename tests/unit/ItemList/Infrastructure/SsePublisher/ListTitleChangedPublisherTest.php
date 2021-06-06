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
use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\SsePublisher\ListTitleChangedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListTitleChangedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListTitleChangedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/lists';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ListTitleChangedPublisher
     */
    private $listTitleChangedPublisher;

    /**
     * @var ListTitleChanged
     */
    private $listTitleChangedEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->listTitleChangedPublisher = new ListTitleChangedPublisher($this->ssePublisher, self::URL);

        $this->listTitleChangedEvent = new ListTitleChanged((string) ListId::generate(), 'Doing');
    }

    /**
     * @test
     */
    public function it_publishes_the_list_title_changed_event(): void
    {
        ($this->listTitleChangedPublisher)($this->listTitleChangedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->listTitleChangedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->listTitleChangedEvent->aggregateId(),
                'title' => (string) $this->listTitleChangedEvent->title()
            ]
        ]));
    }
}
