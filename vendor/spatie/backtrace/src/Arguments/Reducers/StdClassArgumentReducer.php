<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;
use stdClass;

class StdClassArgumentReducer extends ArrayArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if (! $argument instanceof stdClass) {
            return UnReducedArgument::create();
        }

        return parent::reduceArgument((array) $argument, stdClass::class);
    }
}
