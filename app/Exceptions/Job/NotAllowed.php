<?php

namespace App\Exceptions\Job;

use Exception;

/**
 * A job refusing the action it was given, because of the state of the record or
 * because the plan does not allow it. The message is for the person who asked.
 *
 * This is not a fault: nothing went wrong, the answer is no. The web pages show
 * it as a warning through Jobs::ajaxDispatch(), and the API answers 403 with the
 * message through Handler::getStatusCode().
 */
class NotAllowed extends Exception
{
    //
}
