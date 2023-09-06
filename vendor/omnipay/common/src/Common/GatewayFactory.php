<?php
/**
 * Omnipay Gateway Factory class
 */

namespace Omnipay\Common;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Omnipay Gateway Factory class
 *
 * This class abstracts a set of gateways that can be independently
 * registered, accessed, and used.
 *
 * Note that static calls to the Omnipay class are routed to this class by
 * the static call router (__callStatic) in Omnipay.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('ExpressGateway');
 * </code>
 *
 */
class GatewayFactory
{
    /**
     * Internal storage for all available gateways
     *
     * @var array
     */
    private $gateways = array();

    /**
     * All available gateways
     *
     * @return array An array of gateway names
     */
    public function all()
    {
        return $this->gateways;
    }

    /**
     * Replace the list of available gateways
     *
     * @param array $gateways An array of gateway names
     */
    public function replace(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * Register a new gateway
     *
     * @param string $className Gateway name
     */
    public function register($className)
    {
        if (!in_array($className, $this->gateways)) {
            $this->gateways[] = $className;
        }
    }

    /**
     * Create a new gateway instance
     *
     * @param string               $class       Gateway name
     * @param ClientInterface|null $httpClient  A HTTP Client implementation
     * @param HttpRequest|null     $httpRequest A Symfony HTTP Request implementation
     * @throws RuntimeException                 If no such gateway is found
     * @return GatewayInterface                 An object of class $class is created and returned
     */
    public function create($class, ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $class = Helper::getGatewayClassName($class);

        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return new $class($httpClient, $httpRequest);
    }
}
