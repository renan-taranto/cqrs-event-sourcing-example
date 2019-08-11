<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Domain\ValueObject;

/**
 * Class Title
 * @package Taranto\ListMaker\Shared\Domain\ValueObject
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class Title
{
    /**
     * @var string
     */
    private $title;

    /**
     * @param string $title
     * @return Title
     */
    public static function fromString(string $title): self
    {
        return new self($title);
    }

    /**
     * Title constructor.
     * @param string $title
     */
    private function __construct(string $title)
    {
        if ($title === "") {
            throw new \InvalidArgumentException("Title must not be an empty string.");
        }

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
    }

    public function equals(Title $other): bool
    {
        return $this->title === $other->title;
    }
}
