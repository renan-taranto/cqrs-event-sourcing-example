<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Ui\Web\Controller;

use Symfony\Component\HttpFoundation\Request;
use Taranto\ListMaker\Shared\Domain\Message\Command;

/**
 * Class CommandFactory
 * @package Taranto\ListMaker\Shared\Ui\Web\Controller
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CommandFactory
{
    const AGGREGATE_ID_ATTRIBUTE = 'id';
    const COMMAND_CLASS_ATTRIBUTE = 'command_class';

    /**
     * @param Request $request
     * @return Command
     * @throws \Exception
     */
    public function fromHttpRequest(Request $request): Command
    {
        $commandClass = $this->getCommandClass($request);
        return $commandClass::request(
            $this->getAggregateId($request),
            $this->getCommandPayload($request)
        );
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    private function getCommandClass(Request $request): string
    {
        $commandClass = $request->attributes->get(self::COMMAND_CLASS_ATTRIBUTE);
        if ($commandClass === null) {
            throw new \Exception('The "command_class" attribute was not found in the request.');
        }

        return $commandClass;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getAggregateId(Request $request): string
    {
        $aggregateId = $this->getAggregateIdFromUri($request) ?: $this->getAggregateIdFromPayload($request);
        return $aggregateId ?: '';
    }

    /**
     * @param Request $request
     * @return string|null
     */
    private function getAggregateIdFromUri(Request $request): ?string
    {
        return $request->get(self::AGGREGATE_ID_ATTRIBUTE);
    }

    /**
     * @param Request $request
     * @return string|null
     */
    private function getAggregateIdFromPayload(Request $request): ?string
    {
        return $this->getRequestPayload($request)[self::AGGREGATE_ID_ATTRIBUTE] ?? null;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getCommandPayload(Request $request): array
    {
        $requestPayload = $this->getRequestPayload($request);
        unset($requestPayload[self::AGGREGATE_ID_ATTRIBUTE]);
        return $requestPayload;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getRequestPayload(Request $request): array
    {
        return \json_decode($request->getContent(), true) ?: [];
    }
}
