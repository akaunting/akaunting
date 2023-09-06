<?php

namespace Spatie\FlareClient\Truncation;

interface TruncationStrategy
{
    /**
     * @param array<int|string, mixed> $payload
     *
     * @return array<int|string, mixed>
     */
    public function execute(array $payload): array;
}
