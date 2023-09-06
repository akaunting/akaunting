<?php
/**
 * Base payment gateway class
 */

namespace Omnipay\Common;

use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base payment gateway class
 *
 * This abstract class should be extended by all payment gateways
 * throughout the Omnipay system.  It enforces implementation of
 * the GatewayInterface interface and defines various common attributes
 * and methods that all gateways should have.
 *
 * Example:
 *
 * <code>
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
 */
abstract class AbstractGateway implements GatewayInterface
{
    use ParametersTrait {
        setParameter as traitSetParameter;
        getParameter as traitGetParameter;
    }

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    /**
     * Create a new gateway instance
     *
     * @param ClientInterface          $httpClient  A HTTP client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->initialize();
    }

    /**
     * Get the short name of the Gateway
     *
     * @return string
     */
    public function getShortName()
    {
        return Helper::getGatewayShortName(get_class($this));
    }

    /**
     * Initialize this gateway with default parameters
     *
     * @param  array $parameters
     * @return $this
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        // set default parameters
        foreach ($this->getDefaultParameters() as $key => $value) {
            if (is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array();
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->traitGetParameter($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function setParameter($key, $value)
    {
        return $this->traitSetParameter($key, $value);
    }

    /**
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    /**
     * @param  boolean $value
     * @return $this
     */
    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return strtoupper($this->getParameter('currency'));
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    /**
     * Supports Authorize
     *
     * @return boolean True if this gateway supports the authorize() method
     */
    public function supportsAuthorize()
    {
        return method_exists($this, 'authorize');
    }

    /**
     * Supports Complete Authorize
     *
     * @return boolean True if this gateway supports the completeAuthorize() method
     */
    public function supportsCompleteAuthorize()
    {
        return method_exists($this, 'completeAuthorize');
    }

    /**
     * Supports Capture
     *
     * @return boolean True if this gateway supports the capture() method
     */
    public function supportsCapture()
    {
        return method_exists($this, 'capture');
    }

    /**
     * Supports Purchase
     *
     * @return boolean True if this gateway supports the purchase() method
     */
    public function supportsPurchase()
    {
        return method_exists($this, 'purchase');
    }

    /**
     * Supports Complete Purchase
     *
     * @return boolean True if this gateway supports the completePurchase() method
     */
    public function supportsCompletePurchase()
    {
        return method_exists($this, 'completePurchase');
    }

    /**
     * Supports Fetch Transaction
     *
     * @return boolean True if this gateway supports the fetchTransaction() method
     */
    public function supportsFetchTransaction()
    {
        return method_exists($this, 'fetchTransaction');
    }

    /**
     * Supports Refund
     *
     * @return boolean True if this gateway supports the refund() method
     */
    public function supportsRefund()
    {
        return method_exists($this, 'refund');
    }

    /**
     * Supports Void
     *
     * @return boolean True if this gateway supports the void() method
     */
    public function supportsVoid()
    {
        return method_exists($this, 'void');
    }

    /**
     * Supports AcceptNotification
     *
     * @return boolean True if this gateway supports the acceptNotification() method
     */
    public function supportsAcceptNotification()
    {
        return method_exists($this, 'acceptNotification');
    }

    /**
     * Supports CreateCard
     *
     * @return boolean True if this gateway supports the create() method
     */
    public function supportsCreateCard()
    {
        return method_exists($this, 'createCard');
    }

    /**
     * Supports DeleteCard
     *
     * @return boolean True if this gateway supports the delete() method
     */
    public function supportsDeleteCard()
    {
        return method_exists($this, 'deleteCard');
    }

    /**
     * Supports UpdateCard
     *
     * @return boolean True if this gateway supports the update() method
     */
    public function supportsUpdateCard()
    {
        return method_exists($this, 'updateCard');
    }

    /**
     * Create and initialize a request object
     *
     * This function is usually used to create objects of type
     * Omnipay\Common\Message\AbstractRequest (or a non-abstract subclass of it)
     * and initialise them with using existing parameters from this gateway.
     *
     * Example:
     *
     * <code>
     *   class MyRequest extends \Omnipay\Common\Message\AbstractRequest {};
     *
     *   class MyGateway extends \Omnipay\Common\AbstractGateway {
     *     function myRequest($parameters) {
     *       $this->createRequest('MyRequest', $parameters);
     *     }
     *   }
     *
     *   // Create the gateway object
     *   $gw = Omnipay::create('MyGateway');
     *
     *   // Create the request object
     *   $myRequest = $gw->myRequest($someParameters);
     * </code>
     *
     * @param string $class The request class name
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    protected function createRequest($class, array $parameters)
    {
        $obj = new $class($this->httpClient, $this->httpRequest);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    /**
     * Get the global default HTTP client.
     *
     * @return ClientInterface
     */
    protected function getDefaultHttpClient()
    {
        return new Client();
    }

    /**
     * Get the global default HTTP request.
     *
     * @return HttpRequest
     */
    protected function getDefaultHttpRequest()
    {
        return HttpRequest::createFromGlobals();
    }
}
