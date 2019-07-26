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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Taranto\ListMaker\Domain\Model\Common\AggregateRootNotFound;

/**
 * Class CommandController
 * @package Taranto\ListMaker\Ui\Web\Controller
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CommandController
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var CommandFactory
     */
    private $commandFactory;

    /**
     * CommandController constructor.
     * @param MessageBusInterface $commandBus
     * @param CommandFactory $commandFactory
     */
    public function __construct(MessageBusInterface $commandBus, CommandFactory $commandFactory)
    {
        $this->commandBus = $commandBus;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $command = $this->commandFactory->fromHttpRequest($request);

        try {
            $this->commandBus->dispatch($command);
        } catch (AggregateRootNotFound $ex) {
            return JsonResponse::create(['errors' => [$ex->getMessage()]], JsonResponse::HTTP_NOT_FOUND);
        }

        return JsonResponse::create(null, JsonResponse::HTTP_ACCEPTED);
    }
}
