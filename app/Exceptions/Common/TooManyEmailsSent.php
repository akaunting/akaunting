<?php

namespace App\Exceptions\Common;

use RuntimeException;
use Throwable;

class TooManyEmailsSent extends RuntimeException
{
    public function __construct(string $message = '', int $code = 429, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
