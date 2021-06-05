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
use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Board\Infrastructure\SsePublisher\BoardReopenedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class BoardReopenedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardReopenedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/boards';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var BoardReopenedPublisher
     */
    private $boardReopenedPublisher;

    /**
     * @var BoardReopened
     */
    private $boardReopenedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->boardReopenedPublisher = new BoardReopenedPublisher($this->ssePublisher, self::URL);

        $this->boardReopenedEvent = new BoardReopened((string) BoardId::generate());
    }

    /**
     * @test
     */
    public function it_publishes_the_board_reopened_event(): void
    {
        ($this->boardReopenedPublisher)($this->boardReopenedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->boardReopenedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->boardReopenedEvent->aggregateId()
            ]
        ]));
    }
}
