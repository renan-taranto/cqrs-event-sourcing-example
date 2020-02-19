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
 * Class ListIsArchivedValidator
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListIsArchivedValidator extends ConstraintValidator
{
    const BOARD_COLLECTION = 'boards';

    /**
     * @var MongoCollectionProvider
     */
    private $mongoCollectionProvider;

    /**
     * ListIsArchivedValidator constructor.
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
        if (!$constraint instanceof ListIsArchived) {
            throw new UnexpectedTypeException($constraint, ListIsArchived::class);
        }

        $collection = $this->mongoCollectionProvider->getCollection(self::BOARD_COLLECTION);
        if ($collection->findOne(['archivedLists.id' => $value]) === null) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
