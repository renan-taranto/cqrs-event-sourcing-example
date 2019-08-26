<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Tests;

use Codeception\Test\Unit;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateHistory;
use Taranto\ListMaker\Shared\Domain\Aggregate\AggregateRoot;
use Taranto\ListMaker\Shared\Domain\Aggregate\IdentifiesAggregate;

/**
 * Class AggregateRootTestCase
 * @package Taranto\ListMaker\Tests
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class AggregateRootTestCase extends Unit
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * @var AggregateRoot
     */
    private $aggregateRoot;

    protected function withAggregateId(IdentifiesAggregate $aggregateId): self
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    /**
     * @param array $events
     * @return AggregateRootTestCase
     * @throws \Taranto\ListMaker\Shared\Domain\Aggregate\CorruptAggregateHistory
     */
    protected function given(array $events): self
    {
        $this->aggregateRoot = $this->getAggregateRootClass()::reconstituteFrom(
            new AggregateHistory($this->aggregateId, $events)
        );

        return $this;
    }

    /**
     * @param callable $when
     * @return AggregateRootTestCase
     */
    protected function when(callable $when): self
    {
        if ($this->aggregateRoot !== null) {
            $when($this->aggregateRoot);

            return $this;
        }

        $this->aggregateRoot = $when();

        return $this;
    }

    /**
     * @param $expectedEvents
     */
    protected function then($expectedEvents): void
    {
        expect($expectedEvents)->equals($this->aggregateRoot->popRecordedEvents()->toArray());
    }

    /**
     * @return string
     */
    abstract protected function getAggregateRootClass(): string;
}
