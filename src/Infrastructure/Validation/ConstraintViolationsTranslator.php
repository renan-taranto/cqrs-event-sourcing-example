<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Interface ConstraintViolationsTranslator
 * @package Taranto\ListMaker\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface ConstraintViolationsTranslator
{
    /**
     * @param ConstraintViolationListInterface $violationList
     * @return array
     */
    public function translate(ConstraintViolationListInterface $violationList): array;
}
