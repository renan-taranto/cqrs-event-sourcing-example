<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Taranto\ListMaker\Infrastructure\Persistence\Projection\MongoCollectionFactory;

/**
 * Class UniqueAggregateValidator
 * @package Taranto\ListMaker\Infrastructure\Validation\Constraints
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AggregateDoesNotExistValidator extends ConstraintValidator
{
    /**
     * @var MongoCollectionFactory
     */
    private $mongoCollectionFactory;

    /**
     * AggregateDoesNotExistValidator constructor.
     * @param MongoCollectionFactory $mongoCollectionFactory
     */
    public function __construct(MongoCollectionFactory $mongoCollectionFactory)
    {
        $this->mongoCollectionFactory = $mongoCollectionFactory;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AggregateDoesNotExist) {
            throw new UnexpectedTypeException($constraint, AggregateDoesNotExist::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        $collection = $this->mongoCollectionFactory->createCollection($constraint->collectionName);
        if ($collection->findOne([$constraint->idField => $value]) !== null) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
