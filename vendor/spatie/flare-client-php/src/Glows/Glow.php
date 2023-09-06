<?php

namespace Spatie\FlareClient\Glows;

use Spatie\FlareClient\Concerns\UsesTime;
use Spatie\FlareClient\Enums\MessageLevels;

class Glow
{
    use UsesTime;

    protected string $name;

    /** @var array<int, mixed> */
    protected array $metaData = [];

    protected string $messageLevel;

    protected float $microtime;

    /**
     * @param string $name
     * @param string $messageLevel
     * @param array<int, mixed>  $metaData
     * @param float|null $microtime
     */
    public function __construct(
        string $name,
        string $messageLevel = MessageLevels::INFO,
        array $metaData = [],
        ?float $microtime = null
    ) {
        $this->name = $name;
        $this->messageLevel = $messageLevel;
        $this->metaData = $metaData;
        $this->microtime = $microtime ?? microtime(true);
    }

    /**
     * @return array{time: int, name: string, message_level: string, meta_data: array, microtime: float}
     */
    public function toArray(): array
    {
        return [
            'time' => $this->getCurrentTime(),
            'name' => $this->name,
            'message_level' => $this->messageLevel,
            'meta_data' => $this->metaData,
            'microtime' => $this->microtime,
        ];
    }
}
