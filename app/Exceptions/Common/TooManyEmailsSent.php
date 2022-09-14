<?php

namespace App\Exceptions\Common;

use Illuminate\Http\Exceptions\ThrottleRequestsException;

class TooManyEmailsSent extends ThrottleRequestsException
{
    //
}
