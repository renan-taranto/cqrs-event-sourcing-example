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
use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Board\Infrastructure\SsePublisher\BoardClosedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class BoardClosedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardClosedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/boards';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var BoardClosedPublisher
     */
    private $boardClosedPublisher;

    /**
     * @var BoardClosed
     */
    private $boardClosedEvent;

    protected function _before(): void
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->boardClosedPublisher = new BoardClosedPublisher($this->ssePublisher, self::URL);

        $this->boardClosedEvent = new BoardClosed((string) BoardId::generate());
    }

    /**
     * @test
     */
    public function it_publishes_the_board_closed_event(): void
    {
        ($this->boardClosedPublisher)($this->boardClosedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->boardClosedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->boardClosedEvent->aggregateId()
            ]
        ]));
    }
}
