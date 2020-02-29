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

use Ramsey\Uuid\Uuid;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;

/**
 * Class ItemId
 * @package Taranto\ListMaker\Item\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ItemId implements IdentifiesAggregate
{
    /**
     * @var string
     */
    private $itemId;

    /**
     * @param string $itemId
     * @return ItemId
     */
    public static function fromString(string $itemId): self
    {
        return new self($itemId);
    }

    private function __construct(string $itemId)
    {
        if (!Uuid::isValid($itemId)) {
            throw new \InvalidArgumentException("Item id must be a valid UUID. '{$itemId}' given.");
        }

        $this->itemId = $itemId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->itemId;
    }

    /**
     * @param IdentifiesAggregate $other
     * @return bool
     */
    public function equals(IdentifiesAggregate $other): bool
    {
        return $other instanceof self && $this->itemId === $other->itemId;
    }

    /**
     * @return ItemId
     * @throws \Exception
     */
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
