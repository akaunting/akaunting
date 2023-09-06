<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;

class MinimalArrayArgumentReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if(! is_array($argument)) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            'array (size='.count($argument).')',
            'array'
        );
    }
}
