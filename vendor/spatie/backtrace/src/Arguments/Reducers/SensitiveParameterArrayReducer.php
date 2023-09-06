<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use SensitiveParameterValue;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;

class SensitiveParameterArrayReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if (! $argument instanceof SensitiveParameterValue) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            'SensitiveParameterValue('.get_debug_type($argument->getValue()).')',
            get_class($argument)
        );
    }
}
