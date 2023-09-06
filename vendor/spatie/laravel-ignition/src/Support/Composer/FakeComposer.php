<?php

namespace Spatie\LaravelIgnition\Support\Composer;

class FakeComposer implements Composer
{
    /** @return array<string, mixed> */
    public function getClassMap(): array
    {
        return [];
    }

    /** @return array<string, mixed> */
    public function getPrefixes(): array
    {
        return [];
    }

    /** @return array<string, mixed> */
    public function getPrefixesPsr4(): array
    {
        return [];
    }
}
