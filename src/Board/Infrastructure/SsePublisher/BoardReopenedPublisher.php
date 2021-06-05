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

use Taranto\ListMaker\Board\Domain\Event\BoardReopened;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class BoardReopenedPublisher
 * @package Taranto\ListMaker\Board\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class BoardReopenedPublisher
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
     * BoardReopenedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $boardsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $boardsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->boardsSseUrl = $boardsSseUrl;
    }

    /**
     * @param BoardReopened $boardReopened
     */
    public function __invoke(BoardReopened $boardReopened): void
    {
        $this->ssePublisher->publish($this->boardsSseUrl, json_encode([
            'eventType' => $boardReopened->eventType(),
            'payload' => [
                'id' => (string) $boardReopened->aggregateId()
            ]
        ]));
    }
}
