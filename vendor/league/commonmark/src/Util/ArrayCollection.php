<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Util;

/**
 * Array collection
 *
 * Provides a wrapper around a standard PHP array.
 *
 * @internal
 *
 * @phpstan-template TKey
 * @phpstan-template TValue
 * @phpstan-implements \IteratorAggregate<TKey, TValue>
 * @phpstan-implements \ArrayAccess<TKey, TValue>
 */
class ArrayCollection implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var array<int|string, mixed>
     * @phpstan-var array<TKey, TValue>
     */
    private $elements;

    /**
     * Constructor
     *
     * @param array<int|string, mixed> $elements
     *
     * @phpstan-param array<TKey, TValue> $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @return mixed|false
     *
     * @phpstan-return TValue|false
     */
    public function first()
    {
        return \reset($this->elements);
    }

    /**
     * @return mixed|false
     *
     * @phpstan-return TValue|false
     */
    public function last()
    {
        return \end($this->elements);
    }

    /**
     * Retrieve an external iterator
     *
     * @return \ArrayIterator<int|string, mixed>
     *
     * @phpstan-return \ArrayIterator<TKey, TValue>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     *
     * @phpstan-param TValue $element
     *
     * @deprecated
     */
    public function add($element): bool
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'add()', '$collection[] = $value'), E_USER_DEPRECATED);

        $this->elements[] = $element;

        return true;
    }

    /**
     * @param int|string $key
     * @param mixed      $value
     *
     * @return void
     *
     * @phpstan-param TKey   $key
     * @phpstan-param TValue $value
     *
     * @deprecated
     */
    public function set($key, $value)
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'set()', '$collection[$key] = $value'), E_USER_DEPRECATED);

        $this->offsetSet($key, $value);
    }

    /**
     * @param int|string $key
     *
     * @return mixed
     *
     * @phpstan-param TKey $key
     *
     * @phpstan-return TValue|null
     *
     * @deprecated
     */
    public function get($key)
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'get()', '$collection[$key]'), E_USER_DEPRECATED);

        return $this->offsetGet($key);
    }

    /**
     * @param int|string $key
     *
     * @return mixed
     *
     * @phpstan-param TKey $key
     *
     * @phpstan-return TValue|null
     *
     * @deprecated
     */
    public function remove($key)
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'remove()', 'unset($collection[$key])'), E_USER_DEPRECATED);

        if (!\array_key_exists($key, $this->elements)) {
            return;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     * @return bool
     *
     * @deprecated
     */
    public function isEmpty(): bool
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'isEmpty()', 'count($collection) === 0'), E_USER_DEPRECATED);

        return empty($this->elements);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     *
     * @phpstan-param TValue $element
     *
     * @deprecated
     */
    public function contains($element): bool
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'contains()', 'in_array($value, $collection->toArray(), true)'), E_USER_DEPRECATED);

        return \in_array($element, $this->elements, true);
    }

    /**
     * @param mixed $element
     *
     * @return mixed|false
     *
     * @phpstan-param TValue $element
     *
     * @deprecated
     */
    public function indexOf($element)
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'indexOf()', 'array_search($value, $collection->toArray(), true)'), E_USER_DEPRECATED);

        return \array_search($element, $this->elements, true);
    }

    /**
     * @param int|string $key
     *
     * @return bool
     *
     * @phpstan-param TKey $key
     *
     * @deprecated
     */
    public function containsKey($key): bool
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4, use "%s" instead.', self::class, 'containsKey()', 'isset($collection[$key])'), E_USER_DEPRECATED);

        return \array_key_exists($key, $this->elements);
    }

    /**
     * Count elements of an object
     *
     * @return int The count as an integer.
     */
    public function count(): int
    {
        return \count($this->elements);
    }

    /**
     * Whether an offset exists
     *
     * @param int|string $offset An offset to check for.
     *
     * @return bool true on success or false on failure.
     *
     * @phpstan-param TKey $offset
     */
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->elements);
    }

    /**
     * Offset to retrieve
     *
     * @param int|string $offset
     *
     * @return mixed|null
     *
     * @phpstan-param TKey $offset
     *
     * @phpstan-return TValue|null
     */
    public function offsetGet($offset)
    {
        return $this->elements[$offset] ?? null;
    }

    /**
     * Offset to set
     *
     * @param int|string|null $offset The offset to assign the value to.
     * @param mixed           $value  The value to set.
     *
     * @return void
     *
     * @phpstan-param TKey|null $offset
     * @phpstan-param TValue    $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     *
     * @param int|string $offset The offset to unset.
     *
     * @return void
     *
     * @phpstan-param TKey $offset
     */
    public function offsetUnset($offset)
    {
        if (!\array_key_exists($offset, $this->elements)) {
            return;
        }

        unset($this->elements[$offset]);
    }

    /**
     * Returns a subset of the array
     *
     * @param int      $offset
     * @param int|null $length
     *
     * @return array<int|string, mixed>
     *
     * @phpstan-return array<TKey, TValue>
     */
    public function slice(int $offset, ?int $length = null): array
    {
        return \array_slice($this->elements, $offset, $length, true);
    }

    /**
     * @return array<int|string, mixed>
     *
     * @phpstan-return array<TKey, TValue>
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * @param array<int|string, mixed> $elements
     *
     * @return $this
     *
     * @phpstan-param array<TKey, TValue> $elements
     *
     * @deprecated
     */
    public function replaceWith(array $elements)
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4.', self::class, 'replaceWith()'), E_USER_DEPRECATED);

        $this->elements = $elements;

        return $this;
    }

    /**
     * @deprecated
     *
     * @return void
     */
    public function removeGaps()
    {
        @trigger_error(sprintf('The "%s:%s" method is deprecated since league/commonmark 1.4.', self::class, 'removeGaps()'), E_USER_DEPRECATED);

        $this->elements = \array_filter($this->elements);
    }
}
