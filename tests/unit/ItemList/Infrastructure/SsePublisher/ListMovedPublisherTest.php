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
use Taranto\ListMaker\Board\Domain\BoardId;
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
     * @var ListMovedPublisher
     */
    private $listMovedPublisher;

    /**
     * @var ListMoved
     */
    private $listMovedEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->listMovedPublisher = new ListMovedPublisher($this->ssePublisher, self::URL);

        $this->listMovedEvent = new ListMoved(
            (string) ListId::generate(),
            3,
            (string) BoardId::generate()
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_list_moved_event(): void
    {
        ($this->listMovedPublisher)($this->listMovedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->listMovedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->listMovedEvent->aggregateId(),
                'position' => $this->listMovedEvent->position()->toInt(),
                'boardId' => (string) $this->listMovedEvent->boardId()
            ]
        ]));
    }
}
