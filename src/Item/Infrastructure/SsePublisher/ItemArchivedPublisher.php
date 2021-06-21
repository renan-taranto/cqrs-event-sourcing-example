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

use Taranto\ListMaker\Item\Domain\Event\ItemArchived;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemArchivedPublisher
 * @package Taranto\ListMaker\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemArchivedPublisher
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
     * ItemArchivedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $itemsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $itemsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->itemsSseUrl = $itemsSseUrl;
    }

    /**
     * @param ItemArchived $itemArchived
     */
    public function __invoke(ItemArchived $itemArchived): void
    {
        $this->ssePublisher->publish($this->itemsSseUrl, json_encode([
            'eventType' => $itemArchived->eventType(),
            'payload' => [
                'id' => (string) $itemArchived->aggregateId()
            ]
        ]));
    }
}
