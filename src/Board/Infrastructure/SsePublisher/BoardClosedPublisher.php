<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Infrastructure\SsePublisher;

use Taranto\ListMaker\Board\Domain\Event\BoardClosed;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class BoardClosedPublisher
 * @package Taranto\ListMaker\Board\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardClosedPublisher
{
    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var string
     */
    private $boardsSseUrl;

    /**
     * BoardClosedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $boardsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $boardsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->boardsSseUrl = $boardsSseUrl;
    }

    /**
     * @param BoardClosed $boardClosed
     */
    public function __invoke(BoardClosed $boardClosed): void
    {
        $this->ssePublisher->publish($this->boardsSseUrl, json_encode([
            'eventType' => $boardClosed->eventType(),
            'payload' => [
                'id' => (string) $boardClosed->aggregateId()
            ]
        ]));
    }
}
