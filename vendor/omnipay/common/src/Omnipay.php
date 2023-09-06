<?php
/**
 * Omnipay class
 */

namespace Omnipay;

use Omnipay\Common\GatewayFactory;
use Omnipay\Common\Http\ClientInterface;

/**
 * Omnipay class
 *
 * Provides static access to the gateway factory methods.  This is the
 * recommended route for creation and establishment of payment gateway
 * objects via the standard GatewayFactory.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('ExpressGateway');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(...);
 *
 *   // Get the gateway parameters.
 *   $parameters = $gateway->getParameters();
 *
 *   // Create a credit card object
 *   $card = new CreditCard(...);
 *
 *   // Do an authorisation transaction on the gateway
 *   if ($gateway->supportsAuthorize()) {
 *       $gateway->authorize(...);
 *   } else {
 *       throw new \Exception('Gateway does not support authorize()');
 *   }
 * </code>
 *
 * For further code examples see the *omnipay-example* repository on github.
 *
 * @method static array  all()
 * @method static array  replace(array $gateways)
 * @method static string register(string $className)
 * @method static array  find()
 * @method static array  getSupportedGateways()
 * @codingStandardsIgnoreStart
 * @method static \Omnipay\Common\GatewayInterface create(string $class, ClientInterface $httpClient = null, \Symfony\Component\HttpFoundation\Request $httpRequest = null)
 * @codingStandardsIgnoreEnd
 *
 * @see \Omnipay\Common\GatewayFactory
 */
class Omnipay
{

    /**
     * Internal factory storage
     *
     * @var GatewayFactory
     */
    private static $factory;

    /**
     * Get the gateway factory
     *
     * Creates a new empty GatewayFactory if none has been set previously.
     *
     * @return GatewayFactory A GatewayFactory instance
     */
    public static function getFactory()
    {
        if (is_null(self::$factory)) {
            self::$factory = new GatewayFactory;
        }

        return self::$factory;
    }

    /**
     * Set the gateway factory
     *
     * @param GatewayFactory $factory A GatewayFactory instance
     */
    public static function setFactory(GatewayFactory $factory = null)
    {
        self::$factory = $factory;
    }

    /**
     * Static function call router.
     *
     * All other function calls to the Omnipay class are routed to the
     * factory.  e.g. Omnipay::getSupportedGateways(1, 2, 3, 4) is routed to the
     * factory's getSupportedGateways method and passed the parameters 1, 2, 3, 4.
     *
     * Example:
     *
     * <code>
     *   // Create a gateway for the PayPal ExpressGateway
     *   $gateway = Omnipay::create('ExpressGateway');
     * </code>
     *
     * @see GatewayFactory
     *
     * @param string $method     The factory method to invoke.
     * @param array  $parameters Parameters passed to the factory method.
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $factory = self::getFactory();

        return call_user_func_array(array($factory, $method), $parameters);
    }
}
