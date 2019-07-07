<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Domain\Aggregate;

/**
 * Class ImmutableArray
 * @package Taranto\ListMaker\Domain\Aggregate
 * @author Renan Taranto <renantaranto@gmail.com>
 */
abstract class ImmutableArray extends \SplFixedArray implements \Countable, \Iterator, \ArrayAccess
{
    /**
     * ImmutableArray constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        parent::__construct(count($items));
        $i = 0;
        foreach($items as $item) {
            $this->guardType($item);
            parent::offsetSet($i++, $item);
        }
    }

    /**
     * @throws \InvalidArgumentException Throw when the item is not an instance of the accepted type.
     * @param $item
     */
    abstract protected function guardType($item): void;

    /**
     * @return int
     */
    final public function count(): int
    {
        return parent::count();
    }

    /**
     * @return mixed
     */
    final public function current()
    {
        return parent::current();
    }

    /**
     * @return int
     */
    final public function key(): int
    {
        return parent::key();
    }

    final public function next(): void
    {
        parent::next();
    }

    final public function rewind(): void
    {
        parent::rewind();
    }

    /**
     * @return bool
     */
    final public function valid(): bool
    {
        return parent::valid();
    }

    /**
     * @param int|mixed $offset
     * @return bool
     */
    final public function offsetExists($offset): bool
    {
        return parent::offsetExists($offset);
    }

    /**
     * @param int|mixed $offset
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }

    /**
     * @param int|mixed $offset
     * @param mixed $value
     */
    final public function offsetSet($offset, $value)
    {
        throw new ArrayIsImmutable();
    }

    /**
     * @param int|mixed $offset
     */
    final public function offsetUnset($offset)
    {
        throw new ArrayIsImmutable();
    }

    /**
     * @param int $size
     * @return bool|void
     */
    final public function setSize($size)
    {
        throw new ArrayIsImmutable();
    }
}
