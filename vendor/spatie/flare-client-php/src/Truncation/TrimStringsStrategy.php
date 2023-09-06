<?php

namespace Spatie\FlareClient\Truncation;

class TrimStringsStrategy extends AbstractTruncationStrategy
{
    /**
     * @return array<int, int>
     */
    public static function thresholds(): array
    {
        return [1024, 512, 256];
    }

    /**
     * @param array<int|string, mixed> $payload
     *
     * @return array<int|string, mixed>
     */
    public function execute(array $payload): array
    {
        foreach (static::thresholds() as $threshold) {
            if (! $this->reportTrimmer->needsToBeTrimmed($payload)) {
                break;
            }

            $payload = $this->trimPayloadString($payload, $threshold);
        }

        return $payload;
    }

    /**
     * @param array<int|string, mixed> $payload
     * @param int $threshold
     *
     * @return array<int|string, mixed>
     */
    protected function trimPayloadString(array $payload, int $threshold): array
    {
        array_walk_recursive($payload, function (&$value) use ($threshold) {
            if (is_string($value) && strlen($value) > $threshold) {
                $value = substr($value, 0, $threshold);
            }
        });

        return $payload;
    }
}
