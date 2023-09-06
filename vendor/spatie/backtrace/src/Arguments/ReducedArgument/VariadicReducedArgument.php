<?php

namespace Spatie\Backtrace\Arguments\ReducedArgument;

use Exception;

class VariadicReducedArgument extends ReducedArgument
{
    public function __construct(array $value)
    {
        foreach ($value as $key => $item) {
            if (! $item instanceof ReducedArgument) {
                throw new Exception('VariadicReducedArgument must be an array of ReducedArgument');
            }

            $value[$key] = $item->value;
        }

        parent::__construct($value, 'array');
    }
}
