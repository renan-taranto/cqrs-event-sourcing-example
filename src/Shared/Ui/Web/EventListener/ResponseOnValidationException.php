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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolation;
use Taranto\ListMaker\Shared\Infrastructure\Validation\Constraints\MongoDocumentExists;
use Taranto\ListMaker\Shared\Infrastructure\Validation\ConstraintViolationsTranslator;

/**
 * Class ResponseOnValidationException
 * @package Taranto\ListMaker\Shared\Ui\Web\EventListener
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ResponseOnValidationException
{
    /**
     * @var ConstraintViolationsTranslator
     */
    private $constraintViolationsTranslator;

    /**
     * ResponseOnValidationException constructor.
     * @param ConstraintViolationsTranslator $constraintViolationsTranslator
     */
    public function __construct(ConstraintViolationsTranslator $constraintViolationsTranslator)
    {
        $this->constraintViolationsTranslator = $constraintViolationsTranslator;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->getException() instanceof ValidationFailedException) {
            return;
        }

        /** @var ConstraintViolation $violation */
        foreach ($event->getException()->getViolations() as $violation) {
            $constraint = $violation->getConstraint();
            if (
                property_exists(get_class($constraint), 'returnsNotFoundResponse') &&
                $constraint->returnsNotFoundResponse
            ) {
                $response = new Response(null, Response::HTTP_NOT_FOUND, ['Content-Type' => null]);
                $event->setResponse($response);
                return;
            }
        }

        $violations = $this->constraintViolationsTranslator->translate($event->getException()->getViolations());
        $response = new Response(json_encode(['errors' => $violations]), Response::HTTP_BAD_REQUEST);
        $event->setResponse($response);
    }
}
