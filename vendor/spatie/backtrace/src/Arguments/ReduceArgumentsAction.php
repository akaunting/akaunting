<?php

namespace Spatie\Backtrace\Arguments;

use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use Spatie\Backtrace\Arguments\ReducedArgument\VariadicReducedArgument;
use Throwable;

class ReduceArgumentsAction
{
    /** @var ArgumentReducers */
    protected $argumentReducers;

    /** @var ReduceArgumentPayloadAction */
    protected $reduceArgumentPayloadAction;

    public function __construct(
        ArgumentReducers $argumentReducers
    ) {
        $this->argumentReducers = $argumentReducers;
        $this->reduceArgumentPayloadAction = new ReduceArgumentPayloadAction($argumentReducers);
    }

    public function execute(
        ?string $class,
        ?string $method,
        ?array $frameArguments
    ): ?array {
        try {
            if ($frameArguments === null) {
                return null;
            }

            $parameters = $this->getParameters($class, $method);

            if ($parameters === null) {
                $arguments = [];

                foreach ($frameArguments as $index => $argument) {
                    $arguments[$index] = ProvidedArgument::fromNonReflectableParameter($index)
                        ->setReducedArgument($this->reduceArgumentPayloadAction->reduce($argument))
                        ->toArray();
                }

                return $arguments;
            }

            $arguments = array_map(
                function ($argument) {
                    return $this->reduceArgumentPayloadAction->reduce($argument);
                },
                $frameArguments,
            );

            $argumentsCount = count($arguments);
            $hasVariadicParameter = false;

            foreach ($parameters as $index => $parameter) {
                if ($index + 1 > $argumentsCount) {
                    $parameter->defaultValueUsed();
                } elseif ($parameter->isVariadic) {
                    $parameter->setReducedArgument(new VariadicReducedArgument(array_slice($arguments, $index)));

                    $hasVariadicParameter = true;
                } else {
                    $parameter->setReducedArgument($arguments[$index]);
                }

                $parameters[$index] = $parameter->toArray();
            }

            if ($this->moreArgumentsProvidedThanParameters($arguments, $parameters, $hasVariadicParameter)) {
                for ($i = count($parameters); $i < count($arguments); $i++) {
                    $parameters[$i] = ProvidedArgument::fromNonReflectableParameter(count($parameters))
                        ->setReducedArgument($arguments[$i])
                        ->toArray();
                }
            }

            return $parameters;
        } catch (Throwable $e) {
            return null;
        }
    }

    /** @return null|Array<\Spatie\Backtrace\Arguments\ProvidedArgument> */
    protected function getParameters(
        ?string $class,
        ?string $method
    ): ?array {
        try {
            $reflection = $class !== null
                ? new ReflectionMethod($class, $method)
                : new ReflectionFunction($method);
        } catch (ReflectionException $e) {
            return null;
        }

        return array_map(
            function (ReflectionParameter $reflectionParameter) {
                return ProvidedArgument::fromReflectionParameter($reflectionParameter);
            },
            $reflection->getParameters(),
        );
    }

    protected function moreArgumentsProvidedThanParameters(
        array $arguments,
        array $parameters,
        bool $hasVariadicParameter
    ): bool {
        return count($arguments) > count($parameters) && ! $hasVariadicParameter;
    }
}
