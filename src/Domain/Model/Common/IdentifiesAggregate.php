<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Model\Common;

/**
 * Interface IdentifiesAggregate
 * @package Taranto\ListMaker\Domain\Model\Common
 * @author Renan Taranto <renantaranto@gmail.com>
 */
interface IdentifiesAggregate
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param IdentifiesAggregate $other
     * @return bool
     */
    public function equals(IdentifiesAggregate $other): bool;
}