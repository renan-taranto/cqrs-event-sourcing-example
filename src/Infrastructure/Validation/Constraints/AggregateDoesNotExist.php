<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueAggregate
 * @package Taranto\ListMaker\Infrastructure\Validation\Constraints
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AggregateDoesNotExist extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This id is already in use.';

    /**
     * @var string
     */
    public $idField = 'id';

    /**
     * @var string|null
     */
    public $collectionName = null;

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return ['collectionName'];
    }
}
