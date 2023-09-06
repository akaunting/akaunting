<?php
/**
 * Payment gateway interface
 */

namespace Omnipay\Common;

/**
 * Payment gateway interface
 *
 * This interface class defines the standard functions that any
 * Omnipay gateway needs to define.
 *
 *
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array()) (Optional method)
 *         Receive and handle an instant payment notification (IPN)
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())               (Optional method)
 *         Authorize an amount on the customers card
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())       (Optional method)
 *         Handle return from off-site gateways after authorization
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())                 (Optional method)
 *         Capture an amount you have previously authorized
 * @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())                (Optional method)
 *         Authorize and immediately capture an amount on the customers card
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())        (Optional method)
 *         Handle return from off-site gateways after purchase
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())                  (Optional method)
 *         Refund an already processed transaction
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])             (Optional method)
 *         Fetches transaction information
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())                    (Optional method)
 *         Generally can only be called up to 24 hours after submitting a transaction
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())              (Optional method)
 *         The returned response object includes a cardReference, which can be used for future transactions
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())              (Optional method)
 *         Update a stored card
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())              (Optional method)
 *         Delete a stored card
 */
interface GatewayInterface
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName();

    /**
     * Get gateway short name
     *
     * This name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of this gateway.
     * @return string
     */
    public function getShortName();

    /**
     * Define gateway parameters, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
     * );
     * @return array
     */
    public function getDefaultParameters();

    /**
     * Initialize gateway with parameters
     * @return $this
     */
    public function initialize(array $parameters = array());

    /**
     * Get all gateway parameters
     * @return array
     */
    public function getParameters();
}
