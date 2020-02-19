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

use Ramsey\Uuid\Uuid;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;

/**
 * Class ListId
 * @package Taranto\ListMaker\ItemList\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class ListId implements IdentifiesAggregate
{
    /**
     * @var string
     */
    private $listId;

    /**
     * @param string $listId
     * @return ListId
     */
    public static function fromString(string $listId): self
    {
        return new self($listId);
    }

    /**
     * ListId constructor.
     * @param string $listId
     */
    private function __construct(string $listId)
    {
        if (!Uuid::isValid($listId)) {
            throw new \InvalidArgumentException("List id must be a valid UUID. '{$listId}' given.");
        }

        $this->listId = $listId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->listId;
    }

    /**
     * @param IdentifiesAggregate $other
     * @return bool
     */
    public function equals(IdentifiesAggregate $other): bool
    {
        return $other instanceof self && $this->listId === $other->listId;
    }

    /**
     * @return ListId
     * @throws \Exception
     */
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
