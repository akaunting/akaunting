<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Stroke
{
    public bool $strokeShow = true;

    public string|array $strokeCurve = 'smooth';

    public string $strokeLineCap = 'butt';

    public array $strokeColors = [];

    public int|array $strokeWidth = 2;

    public int|array $strokeDashArray = 0;

    public function setStrokeShow(bool $strokeShow): Chart
    {
        $this->strokeShow = $strokeShow;

        $this->setOption([
            'stroke' => [
                'show' => $strokeShow,
            ],
        ]);

        return $this;
    }

    public function getStrokeShow(): bool
    {
        return $this->strokeShow;
    }

    public function setStrokeCurve(string|array $strokeCurve): Chart
    {
        $this->strokeCurve = $strokeCurve;

        $this->setOption([
            'stroke' => [
                'curve' => $strokeCurve,
            ],
        ]);

        return $this;
    }

    public function getStrokeCurve(): string|array
    {
        return $this->strokeCurve;
    }

    public function setStrokeLineCap(string $strokeLineCap): Chart
    {
        $this->strokeLineCap = $strokeLineCap;

        $this->setOption([
            'stroke' => [
                'lineCap' => $strokeLineCap,
            ],
        ]);

        return $this;
    }

    public function getStrokeLineCap(): string
    {
        return $this->strokeLineCap;
    }

    public function setStrokeColors(array $strokeColors): Chart
    {
        $this->strokeColors = $strokeColors;

        $this->setOption([
            'stroke' => [
                'colors' => $strokeColors,
            ],
        ]);

        return $this;
    }

    public function getStrokeColors(): array
    {
        return $this->strokeColors;
    }

    public function setStrokeWidth(int|array $strokeWidth): Chart
    {
        $this->strokeWidth = $strokeWidth;

        $this->setOption([
            'stroke' => [
                'width' => $strokeWidth,
            ],
        ]);

        return $this;
    }

    public function getStrokeWidth(): int|array
    {
        return $this->strokeWidth;
    }

    public function setStrokeDashArray(int|array $strokeDashArray): Chart
    {
        $this->strokeDashArray = $strokeDashArray;

        $this->setOption([
            'stroke' => [
                'dashArray' => $strokeDashArray,
            ],
        ]);

        return $this;
    }

    public function getStrokeDashArray(): int|array
    {
        return $this->strokeDashArray;
    }
}
