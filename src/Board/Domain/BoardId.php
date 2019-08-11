<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Domain;

use Ramsey\Uuid\Uuid;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;

/**
 * Class BoardId
 * @package Taranto\ListMaker\Board\Domain
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardId implements IdentifiesAggregate
{
    /**
     * @var string;
     */
    private $boardId;

    /**
     * @param string $boardId
     * @return BoardId
     */
    public static function fromString(string $boardId): self
    {
        return new self($boardId);
    }

    /**
     * BoardId constructor.
     * @param string $boardId
     */
    private function __construct(string $boardId)
    {
        if (!Uuid::isValid($boardId)) {
            throw new \InvalidArgumentException("Board id must be a valid UUID. '{$boardId}' given.");
        }

        $this->boardId = $boardId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->boardId;
    }

    /**
     * @param IdentifiesAggregate $other
     * @return bool
     */
    public function equals(IdentifiesAggregate $other): bool
    {
        return $other instanceof self && $this->boardId === $other->boardId;
    }

    /**
     * @return BoardId
     * @throws \Exception
     */
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
