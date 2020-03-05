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
use Taranto\ListMaker\Item\Application\Command\AddItem;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class AddItemPositionValidator
 * @package Taranto\ListMaker\Item\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class AddItemPositionValidator extends ConstraintValidator
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

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AddItemPosition) {
            throw new UnexpectedTypeException($constraint, AddItemPosition::class);
        }
        if (!$value instanceof AddItem) {
            throw new \InvalidArgumentException('$value must be an instance of the AddItem command.');
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
            ['$match' => ['lists.id' => $listId]],
            ['$unwind' => '$lists'],
            ['$match' => ['lists.id' => $listId]],
            ['$project' => ['_id' => false, 'count' => ['$size' => '$lists.items.id']]]
        ])->toArray();
        if ($position > $queryResult[0]['count'] - 1) {
            $this->context->buildViolation($constraint->message)->atPath('position')->addViolation();
        }
    }
}
