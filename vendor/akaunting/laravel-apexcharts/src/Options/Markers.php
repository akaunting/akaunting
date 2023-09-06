<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Markers
{
    public int $markersSize = 4;

    public array $markersColors = [];

    public string|array $markersStrokeColors = '#fff';

    public int|array $markersStrokeWidth = 2;

    public int|float|array $markersStrokeOpacity = 0.9;

    public int|array $markersStrokeDashArray = 0;

    public int|float|array $markersFillOpacity = 1;

    public array $markersDiscrete = [];

    public string $markersShape = 'circle';

    public int $markersRadius = 2;

    public int $markersOffsetX = 0;

    public int $markersOffsetY = 0;

    public mixed $markersOnClick;

    public mixed $markersOnDblClick;

    public bool $markersShowNullDataPoints = true;

    public array $markersHover = [
        'size' => '',
        'sizeOffset' => 3,
    ];

    public function setMarkersSize(int $markersSize): Chart
    {
        $this->markersSize = $markersSize;

        $this->setOption([
            'markers' => [
                'size' => $markersSize,
            ],
        ]);

        return $this;
    }

    public function getMarkersSize(): int
    {
        return $this->markersSize;
    }

    public function setMarkersColors(array $markersColors): Chart
    {
        $this->markersColors = $markersColors;

        $this->setOption([
            'markers' => [
                'colors' => $markersColors,
            ],
        ]);

        return $this;
    }

    public function getMarkersColors(): array
    {
        return $this->markersColors;
    }

    public function setMarkersStrokeColors(string|array $markersStrokeColors): Chart
    {
        $this->markersStrokeColors = $markersStrokeColors;

        $this->setOption([
            'markers' => [
                'strokeColors' => $markersStrokeColors,
            ],
        ]);

        return $this;
    }

    public function getMarkersStrokeColors(): string|array
    {
        return $this->markersStrokeColors;
    }

    public function setMarkersStrokeWidth(int|array $markersStrokeWidth): Chart
    {
        $this->markersStrokeWidth = $markersStrokeWidth;

        $this->setOption([
            'markers' => [
                'strokeWidth' => $markersStrokeWidth,
            ],
        ]);

        return $this;
    }

    public function getMarkersStrokeWidth(): int|array
    {
        return $this->markersStrokeWidth;
    }

    public function setMarkersStrokeOpacity(int|float|array $markersStrokeOpacity): Chart
    {
        $this->markersStrokeOpacity = $markersStrokeOpacity;

        $this->setOption([
            'markers' => [
                'strokeOpacity' => $markersStrokeOpacity,
            ],
        ]);

        return $this;
    }

    public function getMarkersStrokeOpacity(): int|float|array
    {
        return $this->markersStrokeOpacity;
    }

    public function setMarkersStrokeDashArray(int|array $markersStrokeDashArray): Chart
    {
        $this->markersStrokeDashArray = $markersStrokeDashArray;

        $this->setOption([
            'markers' => [
                'strokeDashArray' => $markersStrokeDashArray,
            ],
        ]);

        return $this;
    }

    public function getMarkersStrokeDashArray(): int|array
    {
        return $this->markersStrokeDashArray;
    }

    public function setMarkersFillOpacity(int|float|array $markersFillOpacity): Chart
    {
        $this->markersFillOpacity = $markersFillOpacity;

        $this->setOption([
            'markers' => [
                'fillOpacity' => $markersFillOpacity,
            ],
        ]);

        return $this;
    }

    public function getMarkersFillOpacity(): int|float|array
    {
        return $this->markersFillOpacity;
    }

    public function setMarkersDiscrete(array $markersDiscrete): Chart
    {
        $this->markersDiscrete = $markersDiscrete;

        $this->setOption([
            'markers' => [
                'discrete' => $markersDiscrete,
            ],
        ]);

        return $this;
    }

    public function getMarkersDiscrete(): array
    {
        return $this->markersDiscrete;
    }

    public function setMarkersShape(string $markersShape): Chart
    {
        $this->markersShape = $markersShape;

        $this->setOption([
            'markers' => [
                'shape' => $markersShape,
            ],
        ]);

        return $this;
    }

    public function getMarkersShape(): string
    {
        return $this->markersShape;
    }

    public function setMarkersRadius(int $markersRadius): Chart
    {
        $this->markersRadius = $markersRadius;

        $this->setOption([
            'markers' => [
                'radius' => $markersRadius,
            ],
        ]);

        return $this;
    }

    public function getMarkersRadius(): int
    {
        return $this->markersRadius;
    }

    public function setMarkersOffsetX(int $markersOffsetX): Chart
    {
        $this->markersOffsetX = $markersOffsetX;

        $this->setOption([
            'markers' => [
                'offsetX' => $markersOffsetX,
            ],
        ]);

        return $this;
    }

    public function getMarkersOffsetX(): int
    {
        return $this->markersOffsetX;
    }

    public function setMarkersOffsetY(int $markersOffsetY): Chart
    {
        $this->markersOffsetY = $markersOffsetY;

        $this->setOption([
            'markers' => [
                'offsetY' => $markersOffsetY,
            ],
        ]);

        return $this;
    }

    public function getMarkersOffsetY(): int
    {
        return $this->markersOffsetY;
    }

    public function setMarkersOnClick(mixed $markersOnClick): Chart
    {
        $this->markersOnClick = $markersOnClick;

        $this->setOption([
            'markers' => [
                'onClick' => $markersOnClick,
            ],
        ]);

        return $this;
    }

    public function getMarkersOnClick(): mixed
    {
        return $this->markersOnClick;
    }

    public function setMarkersOnDblClick(mixed $markersOnDblClick): Chart
    {
        $this->markersOnDblClick = $markersOnDblClick;

        $this->setOption([
            'markers' => [
                'onDblClick' => $markersOnDblClick,
            ],
        ]);

        return $this;
    }

    public function getMarkersOnDblClick(): mixed
    {
        return $this->markersOnDblClick;
    }

    public function setMarkersShowNullDataPoints(bool $markersShowNullDataPoints): Chart
    {
        $this->markersShowNullDataPoints = $markersShowNullDataPoints;

        $this->setOption([
            'markers' => [
                'showNullDataPoints' => $markersShowNullDataPoints,
            ],
        ]);

        return $this;
    }

    public function getMarkersShowNullDataPoints(): bool
    {
        return $this->markersShowNullDataPoints;
    }

    public function setMarkersHover(array $markersHover): Chart
    {
        $this->markersHover = $markersHover;

        $this->setOption([
            'markers' => [
                'hover' => $markersHover,
            ],
        ]);

        return $this;
    }

    public function getMarkersHover(): array
    {
        return $this->markersHover;
    }
}
