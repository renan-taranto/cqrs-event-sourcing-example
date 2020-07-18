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
use Symfony\Component\Serializer\SerializerInterface;
use Taranto\ListMaker\Shared\Domain\Message\Command;

/**
 * Class CommandFactory
 * @package Taranto\ListMaker\Shared\Ui\Web\Controller
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CommandFactory
{
    private const AGGREGATE_ID_PARAMETER = 'id';
    private const AGGREGATE_ID_ATTRIBUTE = 'aggregateId';
    private const COMMAND_CLASS_ATTRIBUTE = 'command_class';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CommandFactory constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return Command
     * @throws \Exception
     */
    public function fromHttpRequest(Request $request): Command
    {
        return $this->serializer->deserialize(
            json_encode($this->getCommandData($request)),
            $this->getCommandClass($request),
            'json'
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getCommandData(Request $request): array
    {
        $commandData = $this->getRequestPayload($request);
        $commandData[self::AGGREGATE_ID_ATTRIBUTE] = $this->getAggregateIdFromRequest($request);

        return $commandData;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getAggregateIdFromRequest(Request $request): string
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
        return $request->get(self::AGGREGATE_ID_PARAMETER);
    }

    /**
     * @param Request $request
     * @return string|null
     */
    private function getAggregateIdFromPayload(Request $request): ?string
    {
        return $this->getRequestPayload($request)[self::AGGREGATE_ID_PARAMETER] ?? null;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getRequestPayload(Request $request): array
    {
        return \json_decode($request->getContent(), true) ?: [];
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
            throw new \Exception('The "command_class" route attribute must be defined in order to create a command from the request.');
        }

        return $commandClass;
    }
}
