<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Validation;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class CommandViolationsTranslator
 * @package Taranto\ListMaker\Shared\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class CommandViolationsTranslator implements ConstraintViolationsTranslator
{
    /**
     * @param ConstraintViolationListInterface $violationList
     * @return array
     */
    public function translate(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];

        /* @var $violation ConstraintViolationInterface */
        foreach ($violationList as $violation) {
            $property = str_replace(['aggregateId', 'payload[', ']'], ['id', '', ''], $violation->getPropertyPath());
            $errors[$property] = $violation->getMessage();
        }

        return $errors;
    }
}
