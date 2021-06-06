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

use Taranto\ListMaker\ItemList\Domain\Event\ListTitleChanged;
use Taranto\ListMaker\Shared\Infrastructure\SsePublisher\SsePublisher;

/**
 * Class ListTitleChangedPublisher
 * @package Taranto\ListMaker\ItemList\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class ListTitleChangedPublisher
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
     * ListTitleChangedPublisher constructor.
     * @param SsePublisher $ssePublisher
     * @param string $listsSseUrl
     */
    public function __construct(SsePublisher $ssePublisher, string $listsSseUrl)
    {
        $this->ssePublisher = $ssePublisher;
        $this->listsSseUrl = $listsSseUrl;
    }

    /**
     * @param ListTitleChanged $listTitleChanged
     */
    public function __invoke(ListTitleChanged $listTitleChanged): void
    {
        $this->ssePublisher->publish($this->listsSseUrl, json_encode([
            'eventType' => $listTitleChanged->eventType(),
            'payload' => [
                'id' => (string) $listTitleChanged->aggregateId(),
                'title' => (string) $listTitleChanged->title()
            ]
        ]));
    }
}
