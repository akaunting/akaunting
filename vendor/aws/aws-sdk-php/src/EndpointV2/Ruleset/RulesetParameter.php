<?php

namespace Aws\EndpointV2\Ruleset;

use Aws\Exception\UnresolvedEndpointException;

/**
 * Houses properties of an individual parameter definition.
 */
class RulesetParameter
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $builtIn;

    /** @var string */
    private $default;

    /** @var array */
    private $required;

    /** @var string */
    private $documentation;

    /** @var boolean */
    private $deprecated;

    public function __construct($name, array $definition)
    {
        $type = ucfirst($definition['type']);
        if ($this->isValidType($type)) {
            $this->type = $type;
        } else {
            throw new UnresolvedEndpointException(
                'Unknown parameter type ' . "`{$type}`" .
                '. Parameters must be of type `String` or `Boolean`.'
            );
        }
        $this->name = $name;
        $this->builtIn = isset($definition['builtIn']) ? $definition['builtIn'] : null;
        $this->default = isset($definition['default']) ? $definition['default'] : null;
        $this->required =  isset($definition['required']) ?
            $definition['required'] : false;
        $this->documentation =  isset($definition['documentation']) ?
            $definition['documentation'] : null;
        $this->deprecated =  isset($definition['deprecated']) ?
            $definition['deprecated'] : false;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getBuiltIn()
    {
        return $this->builtIn;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getDocumentation()
    {
        return $this->documentation;
    }

    /**
     * @return boolean
     */
    public function getDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * Validates that an input parameter matches the type provided in its definition.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function validateInputParam($inputParam)
    {
        $typeMap = [
            'String' => 'is_string',
            'Boolean' => 'is_bool'
        ];

        if ($typeMap[$this->type]($inputParam) === false) {
            throw new UnresolvedEndpointException(
                "Input parameter `{$this->name}` is the wrong type. Must be a {$this->type}."
            );
        }

        if ($this->deprecated) {
            $deprecated = $this->deprecated;
            $deprecationString = "{$this->name} has been deprecated ";
            $msg = isset($deprecated['message']) ? $deprecated['message'] : null;
            $since = isset($deprecated['since']) ? $deprecated['since'] : null;

            if (!is_null($since)) $deprecationString = $deprecationString
                . 'since '. $since . '. ';
            if (!is_null($msg)) $deprecationString = $deprecationString . $msg;

            trigger_error($deprecationString, E_USER_WARNING);
        }
    }

    private function isValidType($type)
    {
        return in_array($type, ['String', 'Boolean']);
    }
}
