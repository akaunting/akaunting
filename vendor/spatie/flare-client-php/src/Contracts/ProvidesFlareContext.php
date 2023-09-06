<?php

namespace Spatie\FlareClient\Contracts;

interface ProvidesFlareContext
{
    /**
     * @return array<int|string, mixed>
     */
    public function context(): array;
}
