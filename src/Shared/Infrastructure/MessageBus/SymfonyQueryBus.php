<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\MessageBus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SymfonyQueryBus
 * @package Taranto\ListMaker\Shared\Infrastructure\MessageBus
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    /**
     * QueryBus constructor.
     * @param MessageBusInterface $queryBus
     */
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @param object|Envelope $message
     * @return mixed
     */
    public function query($message)
    {
        return $this->handle($message);
    }
}
