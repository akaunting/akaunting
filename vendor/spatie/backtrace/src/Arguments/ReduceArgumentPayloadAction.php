<?php

namespace Spatie\Backtrace\Arguments;

use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;

class ReduceArgumentPayloadAction
{
    /** @var \Spatie\Backtrace\Arguments\ArgumentReducers */
    protected $argumentReducers;

    public function __construct(
        ArgumentReducers $argumentReducers
    ) {
        $this->argumentReducers = $argumentReducers;
    }

    public function reduce($argument, bool $includeObjectType = false): ReducedArgument
    {
        foreach ($this->argumentReducers->argumentReducers as $reducer) {
            $reduced = $reducer->execute($argument);

            if ($reduced instanceof ReducedArgument) {
                return $reduced;
            }
        }

        if (gettype($argument) === 'object' && $includeObjectType) {
            return new ReducedArgument(
                'object ('.get_class($argument).')',
                get_debug_type($argument),
            );
        }

        if (gettype($argument) === 'object') {
            return new ReducedArgument('object', get_debug_type($argument), );
        }

        return new ReducedArgument(
            $argument,
            get_debug_type($argument),
        );
    }
}
