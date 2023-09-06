<?php

namespace Spatie\Backtrace\Arguments\Reducers;

use Spatie\Backtrace\Arguments\ArgumentReducers;
use Spatie\Backtrace\Arguments\ReduceArgumentPayloadAction;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgumentContract;
use Spatie\Backtrace\Arguments\ReducedArgument\TruncatedReducedArgument;
use Spatie\Backtrace\Arguments\ReducedArgument\UnReducedArgument;

class ArrayArgumentReducer implements ReducedArgumentContract
{
    /** @var int */
    protected $maxArraySize = 25;

    /** @var \Spatie\Backtrace\Arguments\ReduceArgumentPayloadAction */
    protected $reduceArgumentPayloadAction;

    public function __construct()
    {
        $this->reduceArgumentPayloadAction = new ReduceArgumentPayloadAction(ArgumentReducers::minimal());
    }

    public function execute($argument): ReducedArgumentContract
    {
        if (! is_array($argument)) {
            return UnReducedArgument::create();
        }

        return $this->reduceArgument($argument, 'array');
    }

    protected function reduceArgument(array $argument, string $originalType): ReducedArgumentContract
    {
        foreach ($argument as $key => $value) {
            $argument[$key] = $this->reduceArgumentPayloadAction->reduce(
                $value,
                true
            )->value;
        }

        if (count($argument) > $this->maxArraySize) {
            return new TruncatedReducedArgument(
                array_slice($argument, 0, $this->maxArraySize),
                'array'
            );
        }

        return new ReducedArgument($argument, $originalType);
    }
}
