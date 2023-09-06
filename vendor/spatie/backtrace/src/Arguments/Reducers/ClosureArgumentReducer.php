<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Closure;
use ReflectionFunction;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;

class ClosureArgumentReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if (! $argument instanceof Closure) {
            return UnReducedArgument::create();
        }

        $reflection = new ReflectionFunction($argument);

        if ($reflection->getFileName() && $reflection->getStartLine() && $reflection->getEndLine()) {
            return new ReducedArgument(
                "{$reflection->getFileName()}:{$reflection->getStartLine()}-{$reflection->getEndLine()}",
                'Closure'
            );
        }

        return new ReducedArgument("{$reflection->getFileName()}", 'Closure');
    }
}
