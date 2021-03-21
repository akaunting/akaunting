<?php
/**
 * Issuer
 */

namespace Omnipay\Common;

/**
 * Issuer
 *
 * This class abstracts some functionality around card issuers used in the
 * Omnipay system.
 */
class Issuer
{
    /**
     * The identifier of the issuer.
     *
     * @var string
     */
    protected $id;
    
    /**
     * The full name of the issuer.
     *
     * @var string
     */
    protected $name;
    
    /**
     * The ID of a payment method that the issuer belongs to.
     **
     * @var string
     */
    protected $paymentMethod;

    /**
     * Create a new Issuer
     *
     * @param string      $id            The identifier of this issuer
     * @param string      $name          The name of this issuer
     * @param string|null $paymentMethod The ID of a payment method this issuer belongs to
     */
    public function __construct($id, $name, $paymentMethod = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * The identifier of this issuer
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The name of this issuer
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The ID of a payment method this issuer belongs to
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}
