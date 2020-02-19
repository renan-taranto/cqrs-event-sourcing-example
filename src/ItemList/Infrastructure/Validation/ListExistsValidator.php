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
use Taranto\ListMaker\Shared\Infrastructure\Persistence\Projection\MongoCollectionProvider;

/**
 * Class ListExistsValidator
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListExistsValidator extends ConstraintValidator
{
    const BOARD_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ListExistsValidator constructor.
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
        if (!$constraint instanceof ListExists) {
            throw new UnexpectedTypeException($constraint, ListExists::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        $collection = $this->mongoCollectionProvider->getCollection(self::BOARD_COLLECTION);
        if ($collection->findOne(['$or' => [['lists.id' => $value], ['archivedLists.id' => $value]]]) === null) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
