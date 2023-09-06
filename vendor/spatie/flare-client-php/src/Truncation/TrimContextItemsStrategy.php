<?php

namespace Spatie\FlareClient\Truncation;

class TrimContextItemsStrategy extends AbstractTruncationStrategy
{
    /**
     * @return array<int, int>
     */
    public static function thresholds(): array
    {
        return [100, 50, 25, 10];
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

            $payload['context'] = $this->iterateContextItems($payload['context'], $threshold);
        }

        return $payload;
    }

    /**
     * @param array<int|string, mixed> $contextItems
     * @param int $threshold
     *
     * @return array<int|string, mixed>
     */
    protected function iterateContextItems(array $contextItems, int $threshold): array
    {
        array_walk($contextItems, [$this, 'trimContextItems'], $threshold);

        return $contextItems;
    }

    protected function trimContextItems(mixed &$value, mixed $key, int $threshold): mixed
    {
        if (is_array($value)) {
            if (count($value) > $threshold) {
                $value = array_slice($value, $threshold * -1, $threshold);
            }

            array_walk($value, [$this, 'trimContextItems'], $threshold);
        }

        return $value;
    }
}
