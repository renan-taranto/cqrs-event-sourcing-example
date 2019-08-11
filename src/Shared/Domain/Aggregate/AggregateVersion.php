<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Domain\Aggregate;

/**
 * Class AggregateVersion
 * @package Taranto\ListMaker\Shared\Domain\Aggregate
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class AggregateVersion
{
    /**
     * @var int
     */
    private $aggregateVersion;

    public static function fromVersion(int $version): self
    {
        return new self($version);
    }

    /**
     * AggregateVersion constructor.
     * @param int $version
     */
    private function __construct(int $version)
    {
        if (filter_var($version, FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException("Aggregate version value must be a valid int.");
        }

        $this->aggregateVersion = $version;
    }

    /**
     * @return int
     */
    public function version(): int
    {
        return $this->aggregateVersion;
    }

    /**
     * @return AggregateVersion
     */
    public function next(): self
    {
        return new self(++$this->aggregateVersion);
    }

    public function decreaseBy(int $numberOfVersions): self
    {
        return new self($this->aggregateVersion - $numberOfVersions);
    }

    /**
     * @param AggregateVersion $other
     * @return bool
     */
    public function equals(AggregateVersion $other): bool
    {
        return $this->aggregateVersion === $other->aggregateVersion;
    }
}
