<?php

namespace Dingo\Api\Exception;

use Exception;

class ValidationHttpException extends ResourceException
{
    /**
     * Create a new validation HTTP exception instance.
     *
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param \Exception                           $previous
     * @param array                                $headers
     * @param int                                  $code
     *
     * @return void
     */
    public function __construct($errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        parent::__construct(null, $errors, $previous, $headers, $code);
    }
}
