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
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class ItemExistsValidator
 * @package Taranto\ListMaker\Item\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemExistsValidator extends ConstraintValidator
{
    const BOARD_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ItemExistsValidator constructor.
     * @param MongoCollectionProvider $mongoCollectionProvider
     */
    public function __construct(MongoCollectionProvider $mongoCollectionProvider)
    {
        $this->mongoCollectionProvider = $mongoCollectionProvider;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ItemExists) {
            throw new UnexpectedTypeException($constraint, ItemExists::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        $collection = $this->mongoCollectionProvider->getCollection(self::BOARD_COLLECTION);
        if ($collection->findOne(['$or' => [
                ['lists.items.id' => $value],
                ['lists.archivedItems.id' => $value],
                ['archivedLists.items.id' => $value],
                ['archivedLists.archivedItems.id' => $value]
            ]]) === null
        ) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
