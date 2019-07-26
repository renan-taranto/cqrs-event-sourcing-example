<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Ui\Web\Controller;

use Symfony\Component\HttpFoundation\Request;
use Taranto\ListMaker\Domain\Model\Common\Command;

/**
 * Class CommandFactory
 * @package Taranto\ListMaker\Ui\Web\Controller
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
        $requestPayload = $this->getRequestPayload($request);
        return $commandClass::request(
            $this->getAggregateId($requestPayload),
            $this->getCommandPayload($requestPayload)
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
     * @return array
     */
    private function getRequestPayload(Request $request): array
    {
        return \json_decode($request->getContent(), true);
    }

    /**
     * @param array $requestPayload
     * @return string
     */
    private function getAggregateId(array $requestPayload): string
    {
        return $requestPayload[self::AGGREGATE_ID_ATTRIBUTE] ?? '';
    }

    /**
     * @param array $requestPayload
     * @return array
     */
    private function getCommandPayload(array $requestPayload): array
    {
        unset($requestPayload[self::AGGREGATE_ID_ATTRIBUTE]);
        return $requestPayload;
    }
}
