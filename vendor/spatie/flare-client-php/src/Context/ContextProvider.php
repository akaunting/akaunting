<?php

namespace Spatie\FlareClient\Context;

interface ContextProvider
{
    /**
     * @return array<int, string|mixed>
     */
    public function toArray(): array;
}
