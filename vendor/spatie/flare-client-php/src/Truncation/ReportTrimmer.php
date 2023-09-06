<?php

namespace Spatie\FlareClient\Truncation;

class ReportTrimmer
{
    protected static int $maxPayloadSize = 524288;

    /** @var array<int, class-string<\Spatie\FlareClient\Truncation\TruncationStrategy>> */
    protected array $strategies = [
        TrimStringsStrategy::class,
        TrimStackFrameArgumentsStrategy::class,
        TrimContextItemsStrategy::class,
    ];

    /**
     * @param array<int|string, mixed> $payload
     *
     * @return array<int|string, mixed>
     */
    public function trim(array $payload): array
    {
        foreach ($this->strategies as $strategy) {
            if (! $this->needsToBeTrimmed($payload)) {
                break;
            }

            $payload = (new $strategy($this))->execute($payload);
        }

        return $payload;
    }

    /**
     * @param array<int|string, mixed> $payload
     *
     * @return bool
     */
    public function needsToBeTrimmed(array $payload): bool
    {
        return strlen((string)json_encode($payload)) > self::getMaxPayloadSize();
    }

    public static function getMaxPayloadSize(): int
    {
        return self::$maxPayloadSize;
    }

    public static function setMaxPayloadSize(int $maxPayloadSize): void
    {
        self::$maxPayloadSize = $maxPayloadSize;
    }
}
