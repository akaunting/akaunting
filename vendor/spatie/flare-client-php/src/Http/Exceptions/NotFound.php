<?php

namespace Spatie\FlareClient\Http\Exceptions;

use Spatie\FlareClient\Http\Response;

class NotFound extends BadResponseCode
{
    public static function getMessageForResponse(Response $response): string
    {
        return 'Not found';
    }
}
