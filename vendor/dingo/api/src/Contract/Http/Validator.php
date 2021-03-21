<?php

namespace Dingo\Api\Contract\Http;

use Illuminate\Http\Request as IlluminateRequest;

interface Validator
{
    /**
     * Validate a request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function validate(IlluminateRequest $request);
}
