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

/**
 * Class ListPositionIsValid
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListPositionIsValid extends Constraint
{
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
    public $positionAccessor;

    /**
     * @var string
     */
    public $message = 'Invalid position.';

    public function getRequiredOptions()
    {
        return ['matchId', 'idAccessor', 'positionAccessor'];
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
