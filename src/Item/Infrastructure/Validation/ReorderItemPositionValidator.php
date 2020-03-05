<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Taranto\ListMaker\Item\Application\Command\ReorderItem;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class ReorderItemPositionValidator
 * @package Taranto\ListMaker\Item\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ReorderItemPositionValidator extends ConstraintValidator
{
    private const BOARDS_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ReorderItemPositionValidator constructor.
     * @param MongoCollectionProvider $mongoCollectionProvider
     */
    public function __construct(MongoCollectionProvider $mongoCollectionProvider)
    {
        $this->mongoCollectionProvider = $mongoCollectionProvider;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReorderItemPosition) {
            throw new UnexpectedTypeException($constraint, ReorderItemPosition::class);
        }
        if (!$value instanceof ReorderItem) {
            throw new \InvalidArgumentException('$value must be an instance of the ReorderItem command.');
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
        $itemId = (string) $value->aggregateId();
        $queryResult = $collection->aggregate([
            ['$match' => ['lists.items.id' => $itemId]],
            ['$unwind' => '$lists'],
            ['$match' => ['lists.items.id' => $itemId]],
            ['$project' => ['_id' => false, 'count' => ['$size' => '$lists.items.id']]]
        ])->toArray();
        if ($position > $queryResult[0]['count'] - 1) {
            $this->context->buildViolation($constraint->message)->atPath('toPosition')->addViolation();
        }
    }
}
