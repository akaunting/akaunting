<?php
/**
 * Cart Item interface
 */

namespace Omnipay\Common;

/**
 * Cart Item interface
 *
 * This interface defines the functionality that all cart items in
 * the Omnipay system are to have.
 */
interface ItemInterface
{
    /**
     * Name of the item
     */
    public function getName();

    /**
     * Description of the item
     */
    public function getDescription();

    /**
     * Quantity of the item
     */
    public function getQuantity();

    /**
     * Price of the item
     */
    public function getPrice();
}
