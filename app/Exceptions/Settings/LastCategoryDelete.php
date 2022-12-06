<?php

namespace App\Exceptions\Settings;

use Exception;
use Throwable;

class LastCategoryDelete extends Exception
{
    /*
        100000000   => Akaunting charecters 
        800         => Setting category menu number  
        40          => CRUD (last item)
        1           => First authorized exception

        Code: 100000841
    */
    public function __construct(string $message = '', int $code = 100000841, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
