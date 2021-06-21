<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Infrastructure\SsePublisher;

use Taranto\ListMaker\Item\Domain\Event\ItemMoved;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemMovedPublisher
 * @package Taranto\ListMaker\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemMovedPublisher
{
    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var string
     */
    private $itemsSseUrl;

    /**
     * ItemMovedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $itemsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $itemsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->itemsSseUrl = $itemsSseUrl;
    }

    /**
     * @param ItemMoved $itemMoved
     */
    public function __invoke(ItemMoved $itemMoved): void
    {
        $this->ssePublisher->publish($this->itemsSseUrl, json_encode([
            'eventType' => $itemMoved->eventType(),
            'payload' => [
                'id' => (string) $itemMoved->aggregateId(),
                'position' => $itemMoved->position()->toInt(),
                'listId' => (string) $itemMoved->listId()
            ]
        ]));
    }
}
