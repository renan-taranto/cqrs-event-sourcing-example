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
 * Class AggregateRoot
 * @package Taranto\ListMaker\Domain\Model\Common
 * @author Renan Taranto <renantaranto@gmail.com>
 */
class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    protected $recordedEvents = [];

    /**
     * @var AggregateVersion
     */
    protected $aggregateVersion;

    /**
     * AggregateRoot constructor.
     */
    protected function __construct()
    {
        $this->aggregateVersion = AggregateVersion::fromVersion(0);
    }

    /**
     * @param AggregateHistory $aggregateHistory
     * @return self
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory): self
    {
        $instance = new static();
        foreach ($aggregateHistory as $event) {
            $instance->apply($event);
        }
        return $instance;
    }

    /**
     * @param DomainEvent $event
     */
    protected function recordThat(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;

        $this->apply($event);
    }

    /**
     * @param DomainEvent $event
     */
    protected function apply(DomainEvent $event): void
    {
        $path = explode("\\", get_class($event));
        $method = 'when' . end($path);

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException("Method '{$method}' does not exist.");
        }

        $this->$method($event);
        $this->aggregateVersion = $this->aggregateVersion->next();
    }

    /**
     * @return DomainEvents
     */
    public function popRecordedEvents(): DomainEvents
    {
        $domainEvents = new DomainEvents($this->recordedEvents);

        $this->recordedEvents = [];

        return $domainEvents;
    }

    public function aggregateVersion(): AggregateVersion
    {
        return $this->aggregateVersion;
    }
}
