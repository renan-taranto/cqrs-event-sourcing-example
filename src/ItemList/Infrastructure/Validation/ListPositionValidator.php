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
use Taranto\ListMaker\ItemList\Application\Command\CreateList;
use Taranto\ListMaker\ItemList\Application\Command\MoveList;
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class ListPositionValidator
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListPositionValidator extends ConstraintValidator
{
    private const BOARDS_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ListPositionValidator constructor.
     * @param MongoCollectionProvider $mongoCollectionProvider
     */
    public function __construct(MongoCollectionProvider $mongoCollectionProvider)
    {
        $this->mongoCollectionProvider = $mongoCollectionProvider;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ListPosition) {
            throw new UnexpectedTypeException($constraint, ListPosition::class);
        }

        if (!$value instanceof CreateList && !$value instanceof MoveList) {
            throw new \InvalidArgumentException('$value must be an instance of CreateList or MoveList commands.');
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
        $queryResult = $collection->aggregate([
            ['$match' => ['id' => (string) $value->boardId()]],
            ['$project' => ['count' => ['$size' => '$lists'], '_id' => false]]
        ])->toArray();
        if ($position > $queryResult[0]['count']) {
            $this->context->buildViolation($constraint->message)->atPath('position')->addViolation();
        }
    }
}
