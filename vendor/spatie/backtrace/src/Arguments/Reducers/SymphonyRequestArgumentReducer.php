<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;
use Symfony\Component\HttpFoundation\Request;

class SymphonyRequestArgumentReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if(! $argument instanceof Request) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            "{$argument->getMethod()} {$argument->getUri()}",
            get_class($argument),
        );
    }
}
