<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Infrastructure\SsePublisher;

use Taranto\ListMaker\ItemList\Domain\Event\ListCreated;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListCreatedPublisher
 * @package Taranto\ListMaker\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListCreatedPublisher
{
    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var string
     */
    private $listsSseUrl;

    /**
     * ListCreatedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $listsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $listsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->listsSseUrl = $listsSseUrl;
    }

    /**
     * @param ListCreated $listCreated
     */
    public function __invoke(ListCreated $listCreated): void
    {
        $this->ssePublisher->publish($this->listsSseUrl, json_encode([
            'eventType' => $listCreated->eventType(),
            'payload' => [
                'id' => (string) $listCreated->aggregateId(),
                'title' => (string) $listCreated->title(),
                'position' => $listCreated->position()->toInt(),
                'boardId' => (string) $listCreated->boardId()
            ]
        ]));
    }
}
