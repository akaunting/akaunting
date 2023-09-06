<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Fill
{
    public array $fillColors = [];

    public int|float $fillOpacity = 0.9;

    public string $fillType = 'solid';

    public array $fillGradient = [];

    public array $fillImage = [];

    public array $fillPattern = [];

    public function setFillColors(array $fillColors): Chart
    {
        $this->fillColors = $fillColors;

        $this->setOption([
            'fill' => [
                'colors' => $fillColors,
            ],
        ]);

        return $this;
    }

    public function getFillColors(): array
    {
        return $this->fillColors;
    }

    public function setFillOpacity(int|float $fillOpacity): Chart
    {
        $this->fillOpacity = $fillOpacity;

        $this->setOption([
            'fill' => [
                'opacity' => $fillOpacity,
            ],
        ]);

        return $this;
    }

    public function getFillOpacity(): int|float
    {
        return $this->fillOpacity;
    }

    public function setFillType(string $fillType): Chart
    {
        $this->fillType = $fillType;

        $this->setOption([
            'fill' => [
                'type' => $fillType,
            ],
        ]);

        return $this;
    }

    public function getFillType(): string
    {
        return $this->fillType;
    }

    public function setFillGradient(array $fillGradient): Chart
    {
        $this->fillGradient = $fillGradient;

        $this->setOption([
            'fill' => [
                'gradient' => $fillGradient,
            ],
        ]);

        return $this;
    }

    public function getFillGradient(): array
    {
        return $this->fillGradient;
    }

    public function setFillImage(array $fillImage): Chart
    {
        $this->fillImage = $fillImage;

        $this->setOption([
            'fill' => [
                'image' => $fillImage,
            ],
        ]);

        return $this;
    }

    public function getFillImage(): array
    {
        return $this->fillImage;
    }

    public function setFillPattern(array $fillPattern): Chart
    {
        $this->fillPattern = $fillPattern;

        $this->setOption([
            'fill' => [
                'pattern' => $fillPattern,
            ],
        ]);

        return $this;
    }

    public function getFillPattern(): array
    {
        return $this->fillPattern;
    }
}
