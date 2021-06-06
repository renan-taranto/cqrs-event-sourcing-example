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
use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\ItemList\Domain\ListId;
use Taranto\ListMaker\ItemList\Infrastructure\SsePublisher\ListCreatedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListCreatedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListCreatedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/lists';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var ListCreatedPublisher
     */
    private $listCreatedPublisher;

    /**
     * @var ListCreated
     */
    private $listCreatedEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->listCreatedPublisher = new ListCreatedPublisher($this->ssePublisher, self::URL);

        $this->listCreatedEvent = new ListCreated(
            (string) ListId::generate(),
            'To Do',
            2,
            (string) BoardId::generate()
        );
    }

    /**
     * @test
     */
    public function it_publishes_the_list_created_event(): void
    {
        ($this->listCreatedPublisher)($this->listCreatedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->listCreatedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->listCreatedEvent->aggregateId(),
                'title' => (string) $this->listCreatedEvent->title(),
                'position' => $this->listCreatedEvent->position()->toInt(),
                'boardId' => (string) $this->listCreatedEvent->boardId()
            ]
        ]));
    }
}
