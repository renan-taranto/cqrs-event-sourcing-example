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
 * Class ListExists
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListExists extends Constraint
{
    /**
     * @var string
     */
    public $message = 'List not found.';

    /**
     * @var bool
     */
    public $returnsNotFoundResponse = false;
}
