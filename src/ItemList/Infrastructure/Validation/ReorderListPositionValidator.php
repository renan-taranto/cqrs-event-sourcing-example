<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Taranto\ListMaker\ItemList\Application\Command\ReorderList;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class ReorderListPositionValidator
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ReorderListPositionValidator extends ConstraintValidator
{
    private const BOARDS_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ReorderListPositionValidator constructor.
     * @param MongoCollectionProvider $mongoCollectionProvider
     */
    public function __construct(MongoCollectionProvider $mongoCollectionProvider)
    {
        $this->mongoCollectionProvider = $mongoCollectionProvider;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReorderListPosition) {
            throw new UnexpectedTypeException($constraint, ReorderListPosition::class);
        }

        if (!$value instanceof ReorderList) {
            throw new \InvalidArgumentException('$value must be an instance of the ReorderList command.');
        }

        $position = $value->toPosition()->toInt();
        if ($position === 0) {
            return;
        }
        if ($position < 0) {
            $this->context->buildViolation($constraint->message)->atPath('toPosition')->addViolation();
            return;
        }

        $collection = $this->mongoCollectionProvider->getCollection(self::BOARDS_COLLECTION);
        $queryResult = $collection->aggregate([
            ['$match' => ['lists.id' => (string) $value->aggregateId()]],
            ['$project' => ['count' => ['$size' => '$lists'], '_id' => false]]
        ])->toArray();
        if ($position > $queryResult[0]['count'] - 1) {
            $this->context->buildViolation($constraint->message)->atPath('toPosition')->addViolation();
        }
    }
}
