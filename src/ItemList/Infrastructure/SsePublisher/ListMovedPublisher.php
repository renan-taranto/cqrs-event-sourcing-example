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

use Taranto\ListMaker\ItemList\Application\Query\ListFinder;
use Taranto\ListMaker\ItemList\Domain\Event\ListMoved;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListMovedPublisher
 * @package Taranto\ListMaker\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListMovedPublisher
{
    /**
     * @var ListFinder
     */
    private $listFinder;

    /**
     * @var SsePublisher
     */
    private $ssePublisher;

    /**
     * @var string
     */
    private $listsSseUrl;

    /**
     * ListMovedPublisher constructor.
     * @param ListFinder $listFinder
     * @param SsePublisher $ssePublisher
     * @param string $listsSseUrl
     */
    public function __construct(ListFinder $listFinder, SsePublisher $ssePublisher, string $listsSseUrl)
    {
        $this->listFinder = $listFinder;
        $this->ssePublisher = $ssePublisher;
        $this->listsSseUrl = $listsSseUrl;
    }

    /**
     * @param ListMoved $listMoved
     */
    public function __invoke(ListMoved $listMoved): void
    {
        $list = $this->listFinder->byId((string) $listMoved->aggregateId());

        $this->ssePublisher->publish($this->listsSseUrl, json_encode([
            'eventType' => $listMoved->eventType(),
            'payload' => [
                'id' => (string) $listMoved->aggregateId(),
                'title' => $list['title'],
                'items' => $list['items'],
                'archivedItems' => $list['archivedItems'],
                'position' => $listMoved->position()->toInt(),
                'boardId' => (string) $listMoved->boardId()
            ]
        ]));
    }
}
