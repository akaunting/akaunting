<?php

namespace Spatie\FlareClient\Context;

class ConsoleContextProvider implements ContextProvider
{
    /**
     * @var array<string, mixed>
     */
    protected array $arguments = [];

    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return [
            'arguments' => $this->arguments,
        ];
    }
}
