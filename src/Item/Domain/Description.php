<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Item\Domain;

/**
 * Class Description
 * @package Taranto\ListMaker\Item\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class Description
{
    /**
     * @var string
     */
    private $description;

    /**
     * @param string $description
     * @return Description
     */
    public static function fromString(string $description): self
    {
        return new self($description);
    }

    /**
     * Description constructor.
     * @param string $description
     */
    private function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->description;
    }

    /**
     * @param Description $other
     * @return bool
     */
    public function equals(Description $other): bool
    {
        return $this->description === $other->description;
    }
}
