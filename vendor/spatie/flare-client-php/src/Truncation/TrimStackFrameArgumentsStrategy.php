<?php

namespace Spatie\FlareClient\Truncation;

class TrimStackFrameArgumentsStrategy implements TruncationStrategy
{
    public function execute(array $payload): array
    {
        for ($i = 0; $i < count($payload['stacktrace']); $i++) {
            $payload['stacktrace'][$i]['arguments'] = null;
        }

        return $payload;
    }
}
