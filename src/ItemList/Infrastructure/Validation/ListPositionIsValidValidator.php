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
 * Class ListPositionIsValidValidator
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListPositionIsValidValidator extends ConstraintValidator
{
    const BOARD_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ListPositionIsValidHandler constructor.
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
        if (!$constraint instanceof ListPositionIsValid) {
            throw new UnexpectedTypeException($constraint, ListPositionIsValid::class);
        }

        if (!$value instanceof ReorderList) {
            throw new \InvalidArgumentException('Unable to validate objects other than the ReorderList.');
        }

        try {
            $toPosition = $value->toPosition();
        } catch (\Exception $exception) {
            $toPosition = null;
        }

        if ($value === null || $value === '' || $toPosition === null) {
            return;
        }

        if ($toPosition < 0) {
            $this->context->buildViolation($constraint->message)->atPath('toPosition')->addViolation();
        }

        $collection = $this->mongoCollectionProvider->getCollection(self::BOARD_COLLECTION);
        $listsCount = $collection->aggregate([
            ['$match' => ['lists.id' => (string) $value->aggregateId()]],
            ['$project' => ['count' => ['$size' => '$lists'], '_id' => false]]
        ])->toArray();
        if (!isset($listsCount[0])) {
            return;
        }
        if ($toPosition > $listsCount[0]['count'] - 1) {
            $this->context->buildViolation($constraint->message)->atPath('toPosition')->addViolation();
        }
    }
}
