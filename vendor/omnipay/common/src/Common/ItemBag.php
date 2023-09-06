<?php
/**
 * Cart Item Bag
 */

namespace Omnipay\Common;

/**
 * Cart Item Bag
 *
 * This class defines a bag (multi element set or array) of single cart items
 * in the Omnipay system.
 *
 */
class ItemBag implements \IteratorAggregate, \Countable
{
    /**
     * Item storage
     *
     *
     * @var array
     */
    protected $items;

    /**
     * Constructor
     *
     * @param array $items An array of items
     */
    public function __construct(array $items = array())
    {
        $this->replace($items);
    }

    /**
     * Return all the items
     *
     *
     * @return array An array of items
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Replace the contents of this bag with the specified items
     *
     *
     * @param array $items An array of items
     */
    public function replace(array $items = array())
    {
        $this->items = array();

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * Add an item to the bag
     *
     *
     * @param ItemInterface|array $item An existing item, or associative array of item parameters
     */
    public function add($item)
    {
        if ($item instanceof ItemInterface) {
            $this->items[] = $item;
        } else {
            $this->items[] = new Item($item);
        }
    }

    /**
     * Returns an iterator for items
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Returns the number of items
     *
     * @return int The number of items
     */
    public function count(): int
    {
        return count($this->items);
    }
}
