<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait ForecastDataPoints
{
    public int $forecastDataPointsCount = 0;

    public int|float $forecastDataPointsFillOpacity = 0.5;

    public int $forecastDataPointsStrokeWidth;

    public int $forecastDataPointsDashArray = 4;

    public function setForecastDataPointsCount(int $forecastDataPointsCount): Chart
    {
        $this->forecastDataPointsCount = $forecastDataPointsCount;

        $this->setOption([
            'forecastDataPoints' => [
                'count' => $forecastDataPointsCount,
            ],
        ]);

        return $this;
    }

    public function getForecastDataPointsCount(): int
    {
        return $this->forecastDataPointsCount;
    }

    public function setForecastDataPointsFillOpacity(int|float $forecastDataPointsFillOpacity): Chart
    {
        $this->forecastDataPointsFillOpacity = $forecastDataPointsFillOpacity;

        $this->setOption([
            'forecastDataPoints' => [
                'fillOpacity' => $forecastDataPointsFillOpacity,
            ],
        ]);

        return $this;
    }

    public function getForecastDataPointsFillOpacity(): int|float
    {
        return $this->forecastDataPointsFillOpacity;
    }

    public function setForecastDataPointsStrokeWidth(int $forecastDataPointsStrokeWidth): Chart
    {
        $this->forecastDataPointsStrokeWidth = $forecastDataPointsStrokeWidth;

        $this->setOption([
            'forecastDataPoints' => [
                'strokeWidth' => $forecastDataPointsStrokeWidth,
            ],
        ]);

        return $this;
    }

    public function getForecastDataPointsStrokeWidth(): int
    {
        return $this->forecastDataPointsStrokeWidth;
    }

    public function setForecastDataPointsDashArray(int $forecastDataPointsDashArray): Chart
    {
        $this->forecastDataPointsDashArray = $forecastDataPointsDashArray;

        $this->setOption([
            'forecastDataPoints' => [
                'dashArray' => $forecastDataPointsDashArray,
            ],
        ]);

        return $this;
    }

    public function getForecastDataPointsDashArray(): int
    {
        return $this->forecastDataPointsDashArray;
    }
}
