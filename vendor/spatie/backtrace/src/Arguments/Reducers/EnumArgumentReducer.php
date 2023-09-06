<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;
use UnitEnum;

class EnumArgumentReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if (! $argument instanceof UnitEnum) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            get_class($argument).'::'.$argument->name,
            get_class($argument),
        );
    }
}
