<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;
use Stringable;

class StringableArgumentReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if (! $argument instanceof Stringable) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            (string) $argument,
            get_class($argument),
        );
    }
}
