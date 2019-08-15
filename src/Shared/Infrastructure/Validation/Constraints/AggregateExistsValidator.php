<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class AggregateExistsValidator
 * @package Taranto\ListMaker\Shared\Infrastructure\Validation\Constraints
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AggregateExistsValidator extends ConstraintValidator
{
    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * AggregateDoesNotExistValidator constructor.
     * @param MongoCollectionProvider $mongoCollectionProvider
     */
    public function __construct(MongoCollectionProvider $mongoCollectionProvider)
    {
        $this->mongoCollectionProvider = $mongoCollectionProvider;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AggregateExists) {
            throw new UnexpectedTypeException($constraint, AggregateExists::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        $collection = $this->mongoCollectionProvider->getCollection($constraint->collectionName);
        if ($collection->findOne([$constraint->idField => $value]) === null) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
