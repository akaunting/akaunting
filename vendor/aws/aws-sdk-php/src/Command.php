<?php
namespace Aws;

/**
 * AWS command object.
 */
class Command implements CommandInterface
{
    use HasDataTrait;

    /** @var string */
    private $name;

    /** @var HandlerList */
    private $handlerList;

    /** @var Array */
    private $authSchemes;

    /**
     * Accepts an associative array of command options, including:
     *
     * - @http: (array) Associative array of transfer options.
     *
     * @param string      $name           Name of the command
     * @param array       $args           Arguments to pass to the command
     * @param HandlerList $list           Handler list
     */
    public function __construct($name, array $args = [], HandlerList $list = null)
    {
        $this->name = $name;
        $this->data = $args;
        $this->handlerList = $list ?: new HandlerList();

        if (!isset($this->data['@http'])) {
            $this->data['@http'] = [];
        }
        if (!isset($this->data['@context'])) {
            $this->data['@context'] = [];
        }
    }

    public function __clone()
    {
        $this->handlerList = clone $this->handlerList;
    }

    public function getName()
    {
        return $this->name;
    }

    public function hasParam($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function getHandlerList()
    {
        return $this->handlerList;
    }

    /**
     * For overriding auth schemes on a per endpoint basis when using
     * EndpointV2 provider. Intended for internal use only.
     *
     * @param array $authSchemes
     *
     * @internal
     */
    public function setAuthSchemes(array $authSchemes)
    {
        $this->authSchemes = $authSchemes;
    }

    /**
     * Get auth schemes added to command as required
     * for endpoint resolution
     *
     * @returns array | null
     */
    public function getAuthSchemes()
    {
        return $this->authSchemes;
    }

    /** @deprecated */
    public function get($name)
    {
        return $this[$name];
    }
}
