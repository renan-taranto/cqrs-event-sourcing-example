<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\SsePublisher;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * Class MercureSsePublisher
 * @package Taranto\ListMaker\Shared\Infrastructure\SsePublisher
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MercureSsePublisher implements SsePublisher
{
    /**
     * @var HubInterface
     */
    private $hub;

    /**
     * MercureSsePublisher constructor.
     * @param HubInterface $hub
     */
    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    /**
     * @param string $url
     * @param string $data
     */
    public function publish(string $url, string $data): void
    {
        $this->hub->publish(new Update($url, $data));
    }
}
