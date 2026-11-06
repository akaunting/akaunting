<?php

namespace App\Exceptions\Job;

use Exception;

/**
 * A job that cannot find a record the request pointed at, such as an account id
 * that is not there any more. The API answers 404 with the message.
 */
class NotFound extends Exception
{
    //
}
