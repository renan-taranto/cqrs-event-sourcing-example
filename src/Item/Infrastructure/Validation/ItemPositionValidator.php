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
use Taranto\ListMaker\Item\Application\Command\MoveItem;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class ItemPositionValidator
 * @package Taranto\ListMaker\Item\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemPositionValidator extends ConstraintValidator
{
    private const BOARDS_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ItemPositionValidator constructor.
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
        if (!$constraint instanceof ItemPosition) {
            throw new UnexpectedTypeException($constraint, ItemPosition::class);
        }
        if (!$value instanceof MoveItem) {
            throw new \InvalidArgumentException('$value must be an instance of the MoveItem command.');
        }

        $position = $value->position()->toInt();
        if ($position === 0) {
            return;
        }
        if ($position < 0) {
            $this->context->buildViolation($constraint->message)->atPath('position')->addViolation();
            return;
        }

        $collection = $this->mongoCollectionProvider->getCollection(self::BOARDS_COLLECTION);
        $listId = (string) $value->listId();
        $queryResult = $collection->aggregate([
            ['$unwind' => '$lists'],
            ['$match' => ['lists.id' => $listId]],
            ['$project' => ['_id' => false, 'count' => ['$size' => '$lists.items.id']]]
        ])->toArray();
        if ($position > $queryResult[0]['count']) {
            $this->context->buildViolation($constraint->message)->atPath('position')->addViolation();
        }
    }
}
