<?php

namespace Dingo\Api\Contract\Transformer;

use Dingo\Api\Http\Request;
use Dingo\Api\Transformer\Binding;

interface Adapter
{
    /**
     * Transform a response with a transformer.
     *
     * @param mixed                          $response
     * @param object                         $transformer
     * @param \Dingo\Api\Transformer\Binding $binding
     * @param \Dingo\Api\Http\Request        $request
     *
     * @return array
     */
    public function transform($response, $transformer, Binding $binding, Request $request);
}
