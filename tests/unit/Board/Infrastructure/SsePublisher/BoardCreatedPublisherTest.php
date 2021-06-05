<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests\Unit\Board\Infrastructure\SsePublisher;

use Codeception\Test\Unit;
use Taranto\ListMaker\Board\Domain\BoardId;
use Taranto\ListMaker\Board\Domain\Event\BoardCreated;
use Taranto\ListMaker\Board\Infrastructure\SsePublisher\BoardCreatedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class BoardCreatedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardCreatedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/boards';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var BoardCreatedPublisher
     */
    private $boardCreatedPublisher;

    /**
     * @var BoardCreated
     */
    private $boardCreatedEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->boardCreatedPublisher = new BoardCreatedPublisher($this->ssePublisher, self::URL);

        $this->boardCreatedEvent = new BoardCreated((string) BoardId::generate(), 'Backlog');
    }

    /**
     * @test
     */
    public function it_publishes_the_board_created_event(): void
    {
        ($this->boardCreatedPublisher)($this->boardCreatedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->boardCreatedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->boardCreatedEvent->aggregateId(),
                'title' => (string) $this->boardCreatedEvent->title()
            ]
        ]));
    }
}
