<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Utilities\Date;

class Ping extends ApiController
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // do nothing but override permissions
    }

    /**
     * Responds with a status for heath check.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pong()
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => Date::now(),
        ]);
    }
}
