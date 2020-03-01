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
 * Class MongoArrayIndexValidator
 * @package Taranto\ListMaker\Shared\Infrastructure\Validation\Constraints
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoArrayIndexValidator extends ConstraintValidator
{
    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * MongoArrayIndexExistsValidator constructor.
     * @param MongoCollectionProvider $mongoCollectionProvider
     */
    public function __construct(MongoCollectionProvider $mongoCollectionProvider)
    {
        $this->mongoCollectionProvider = $mongoCollectionProvider;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof MongoArrayIndex) {
            throw new UnexpectedTypeException($constraint, MongoArrayIndex::class);
        }

        $index = call_user_func([$value, $constraint->indexAccessor])->toInt();
        if ($index === 0) {
            return;
        }

        if ($index < 0) {
            $this->context->buildViolation($constraint->message)->atPath($constraint->indexAccessor)->addViolation();
            return;
        }

        $collection = $this->mongoCollectionProvider->getCollection($constraint->collection);
        $queryResult = $collection->aggregate([
            ['$match' => [$constraint->matchId => (string) call_user_func([$value, $constraint->idAccessor])]],
            ['$project' => ['count' => ['$size' => $constraint->arrayPath], '_id' => false]]
        ])->toArray();
        if (!isset($queryResult[0]) || $index > $queryResult[0]['count'] - 1) {
            $this->context->buildViolation($constraint->message)->atPath($constraint->indexAccessor)->addViolation();
        }
    }
}
