<?php

namespace Spatie\LaravelIgnition\ArgumentReducers;

use Illuminate\Database\Eloquent\Model;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;
use Spatie\Backtrace\Arguments\Reducers\ArgumentReducer;

class ModelArgumentReducer implements ArgumentReducer
{
    public function execute(mixed $argument): ReducedArgumentContract
    {
        if (! $argument instanceof Model) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            "{$argument->getKeyName()}:{$argument->getKey()}",
            get_class($argument)
        );
    }
}
