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
use Taranto\ListMaker\Board\Domain\Event\BoardTitleChanged;
use Taranto\ListMaker\Board\Infrastructure\SsePublisher\BoardTitleChangedPublisher;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class BoardTitleChangedPublisherTest
 * @package Taranto\ListMaker\Tests\Unit\Board\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardTitleChangedPublisherTest extends Unit
{
    private const URL = 'https://cqrs-event-sourcing-example.com/boards';

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var BoardTitleChangedPublisher
     */
    private $boardTitleChangedPublisher;

    /**
     * @var BoardTitleChanged
     */
    private $boardTitleChangedEvent;

    protected function _before()
    {
        $this->ssePublisher = \Mockery::spy(SsePublisher::class);
        $this->boardTitleChangedPublisher = new BoardTitleChangedPublisher($this->ssePublisher, self::URL);

        $this->boardTitleChangedEvent = new BoardTitleChanged((string) BoardId::generate(), 'Sprint 2');
    }

    /**
     * @test
     */
    public function it_publishes_the_board_title_changed_event(): void
    {
        ($this->boardTitleChangedPublisher)($this->boardTitleChangedEvent);

        $this->ssePublisher->shouldHaveReceived('publish')->with(self::URL, json_encode([
            'eventType' => $this->boardTitleChangedEvent->eventType(),
            'payload' => [
                'id' => (string) $this->boardTitleChangedEvent->aggregateId(),
                'title' => (string) $this->boardTitleChangedEvent->title()
            ]
        ]));
    }
}
