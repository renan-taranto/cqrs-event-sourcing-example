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
 * Class ListDoesNotExist
 * @package Taranto\ListMaker\ItemList\Infrastructure\Validation
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListDoesNotExist extends Constraint
{
    public $message = 'List id already in use.';
}
