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

use Taranto\ListMaker\ItemList\Domain\Event\ListArchived;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListArchivedPublisher
 * @package Taranto\ListMaker\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListArchivedPublisher
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
     * ListArchivedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $listsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $listsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->listsSseUrl = $listsSseUrl;
    }

    /**
     * @param ListArchived $listArchived
     */
    public function __invoke(ListArchived $listArchived): void
    {
        $this->ssePublisher->publish($this->listsSseUrl, json_encode([
            'eventType' => $listArchived->eventType(),
            'payload' => [
                'id' => (string) $listArchived->aggregateId()
            ]
        ]));
    }
}
