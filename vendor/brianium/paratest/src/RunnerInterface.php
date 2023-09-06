<?php

declare(strict_types=1);

namespace ParaTest;

interface RunnerInterface
{
    public const SUCCESS_EXIT   = 0;
    public const FAILURE_EXIT   = 1;
    public const EXCEPTION_EXIT = 2;

    public function run(): int;
}
