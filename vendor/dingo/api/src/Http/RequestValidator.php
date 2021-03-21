<?php

namespace Dingo\Api\Http;

use Illuminate\Container\Container;
use Dingo\Api\Http\Validation\Accept;
use Dingo\Api\Http\Validation\Domain;
use Dingo\Api\Http\Validation\Prefix;
use Dingo\Api\Contract\Http\Validator;
use Illuminate\Http\Request as IlluminateRequest;

class RequestValidator
{
    /**
     * Container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Array of request validators.
     *
     * @var array
     */
    protected $validators = [
        Domain::class,
        Prefix::class,
    ];

    /**
     * Create a new request validator instance.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Replace the validators.
     *
     * @param array $validators
     *
     * @return void
     */
    public function replace(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * Merge an array of validators.
     *
     * @param array $validators
     *
     * @return void
     */
    public function merge(array $validators)
    {
        $this->validators = array_merge($this->validators, $validators);
    }

    /**
     * Extend the validators.
     *
     * @param string|\Dingo\Api\Http\Validator $validator
     *
     * @return void
     */
    public function extend($validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * Validate a request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function validateRequest(IlluminateRequest $request)
    {
        $passed = false;

        foreach ($this->validators as $validator) {
            $validator = $this->container->make($validator);

            if ($validator instanceof Validator && $validator->validate($request)) {
                $passed = true;
            }
        }

        // The accept validator will always be run once any of the previous validators have
        // been run. This ensures that we only run the accept validator once we know we
        // have a request that is targeting the API.
        if ($passed) {
            $this->container->make(Accept::class)->validate($request);
        }

        return $passed;
    }
}
