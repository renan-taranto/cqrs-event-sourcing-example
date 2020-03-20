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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CommandController
 * @package Taranto\ListMaker\Shared\Ui\Web\Controller
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
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $command = $this->commandFactory->fromHttpRequest($request);
        $this->commandBus->dispatch($command);

        return Response::create(null, Response::HTTP_ACCEPTED, ['Content-Type' => null]);
    }
}
