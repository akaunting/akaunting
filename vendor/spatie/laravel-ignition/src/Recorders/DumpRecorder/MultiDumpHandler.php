<?php

namespace Spatie\LaravelIgnition\Recorders\DumpRecorder;

class MultiDumpHandler
{
    /** @var array<int, callable|null> */
    protected array $handlers = [];

    public function dump(mixed $value): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler) {
                $handler($value);
            }
        }
    }

    public function addHandler(callable $callable = null): self
    {
        $this->handlers[] = $callable;

        return $this;
    }
}
