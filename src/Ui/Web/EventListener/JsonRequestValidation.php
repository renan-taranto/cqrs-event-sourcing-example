<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Ui\Web\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class JsonRequestValidation
 * @package Taranto\ListMaker\Ui\Web\EventListener
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class JsonRequestValidation
{
    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->getRequest()->getMethod() !== Request::METHOD_POST) {
            return;
        }

        \json_decode($event->getRequest()->getContent());
        if (json_last_error() !== JSON_ERROR_NONE || is_numeric($event->getRequest()->getContent())) {
            $response = new JsonResponse(['errors' => ['Invalid request payload.']], JsonResponse::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }
}
