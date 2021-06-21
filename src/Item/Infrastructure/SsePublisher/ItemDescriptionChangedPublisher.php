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

use Taranto\ListMaker\Item\Domain\Event\ItemDescriptionChanged;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemDescriptionChangedPublisher
 * @package Taranto\ListMaker\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemDescriptionChangedPublisher
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
     * ItemDescriptionChangedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $itemsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $itemsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->itemsSseUrl = $itemsSseUrl;
    }

    /**
     * @param ItemDescriptionChanged $itemDescriptionChanged
     */
    public function __invoke(ItemDescriptionChanged $itemDescriptionChanged): void
    {
        $this->ssePublisher->publish($this->itemsSseUrl, json_encode([
            'eventType' => $itemDescriptionChanged->eventType(),
            'payload' => [
                'id' => (string) $itemDescriptionChanged->aggregateId(),
                'description' => (string) $itemDescriptionChanged->description()
            ]
        ]));
    }
}
