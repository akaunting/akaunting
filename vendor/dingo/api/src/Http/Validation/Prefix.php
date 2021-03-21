<?php

namespace Dingo\Api\Http\Validation;

use Illuminate\Http\Request;
use Dingo\Api\Contract\Http\Validator;

class Prefix implements Validator
{
    /**
     * API prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Create a new prefix validator instance.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Validate the request has a prefix and if it matches the configured
     * API prefix.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        $prefix = $this->filterAndExplode($this->prefix);

        $path = $this->filterAndExplode($request->getPathInfo());

        return ! is_null($this->prefix) && $prefix == array_slice($path, 0, count($prefix));
    }

    /**
     * Explode array on slash and remove empty values.
     *
     * @param array $array
     *
     * @return array
     */
    protected function filterAndExplode($array)
    {
        return array_filter(explode('/', $array));
    }
}
