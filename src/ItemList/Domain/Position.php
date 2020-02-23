<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\ItemList\Domain;

/**
 * Class Position
 * @package Taranto\ListMaker\ItemList\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class Position
{
    /**
     * @var int
     */
    private $position;

    /**
     * @param int $position
     * @return Position
     */
    public static function fromInt(int $position): self
    {
        return new self($position);
    }

    /**
     * Position constructor.
     * @param int $position
     */
    private function __construct(int $position)
    {
        if ($position < 0) {
            throw new \InvalidArgumentException('Position must be greater than 0.');
        }

        $this->position = $position;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->position;
    }

    /**
     * @param Position $other
     * @return bool
     */
    public function equals(Position $other): bool
    {
        return $this->position === $other->position;
    }
}
