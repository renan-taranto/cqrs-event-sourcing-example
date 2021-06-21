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

use Taranto\ListMaker\Item\Domain\Event\ItemTitleChanged;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ItemTitleChangedPublisher
 * @package Taranto\ListMaker\Item\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ItemTitleChangedPublisher
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
     * ItemTitleChangedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $itemsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $itemsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->itemsSseUrl = $itemsSseUrl;
    }

    /**
     * @param ItemTitleChanged $itemTitleChanged
     */
    public function __invoke(ItemTitleChanged $itemTitleChanged): void
    {
        $this->ssePublisher->publish($this->itemsSseUrl, json_encode([
            'eventType' => $itemTitleChanged->eventType(),
            'payload' => [
                'id' => (string) $itemTitleChanged->aggregateId(),
                'title' => (string) $itemTitleChanged->title()
            ]
        ]));
    }
}
