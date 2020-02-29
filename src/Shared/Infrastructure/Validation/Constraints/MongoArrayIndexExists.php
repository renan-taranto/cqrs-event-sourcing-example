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

/**
 * Class MongoArrayIndexExists
 * @package Taranto\ListMaker\Shared\Infrastructure\Validation\Constraints
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class MongoArrayIndexExists extends Constraint
{
    /**
     * @var string
     */
    public $collection;

    /**
     * @var string
     */
    public $matchId;

    /**
     * @var string
     */
    public $idAccessor;

    /**
     * @var string
     */
    public $indexAccessor;

    /**
     * @var string
     */
    public $arrayPath;

    /**
     * @var string
     */
    public $message = 'Invalid position.';

    public function getRequiredOptions()
    {
        return [
            'collection',
            'matchId',
            'idAccessor',
            'indexAccessor',
            'arrayPath'
        ];
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
