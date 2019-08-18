<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Ui\Web\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class JsonRequestValidation
 * @package Taranto\ListMaker\Shared\Ui\Web\EventListener
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class JsonRequestValidation
{
    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->getRequest()->getMethod() !== Request::METHOD_POST || empty($event->getRequest()->getContent())) {
            return;
        }

        \json_decode($event->getRequest()->getContent());
        if (json_last_error() !== JSON_ERROR_NONE || is_numeric($event->getRequest()->getContent())) {
            $response = new Response(
                json_encode(['errors' => ['Invalid request payload.']]),
                Response::HTTP_BAD_REQUEST
            );
            $event->setResponse($response);
        }
    }
}
