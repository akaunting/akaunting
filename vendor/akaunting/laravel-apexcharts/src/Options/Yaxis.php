<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Yaxis
{
    public bool $yaxisShow = true;

    public bool $yaxisShowAlways = true;

    public bool $yaxisShowForNullSeries = true;

    public string $yaxisSeriesName = '';

    public bool $yaxisOpposite = false;

    public bool $yaxisReversed = false;

    public bool $yaxisLogarithmic = false;

    public int $yaxisLogBase = 10;

    public int $yaxisTickAmount = 6;

    public int $yaxisMin = 6;

    public int $yaxisMax = 6;

    public bool $yaxisForceNiceScale = false;

    public bool $yaxisFloating = false;

    public int $yaxisDecimalsInFloat;

    public array $yaxisLabels = [];

    public array$yaxisAxisBorder = [];

    public array $yaxisAxisTicks = [];

    public array $yaxisTitle = [];

    public array $yaxisCrosshairs = [];

    public array $yaxisTooltip = [];

    public function setYaxisShow(bool $yaxisShow): Chart
    {
        $this->yaxisShow = $yaxisShow;

        $this->setOption([
            'yaxis' => [
                'show' => $yaxisShow,
            ],
        ]);

        return $this;
    }

    public function getYaxisShow(): bool
    {
        return $this->yaxisShow;
    }

    public function setYaxisShowAlways(bool $yaxisShowAlways): Chart
    {
        $this->yaxisShowAlways = $yaxisShowAlways;

        $this->setOption([
            'yaxis' => [
                'showAlways' => $yaxisShowAlways,
            ],
        ]);

        return $this;
    }

    public function getYaxisShowAlways(): bool
    {
        return $this->yaxisShowAlways;
    }

    public function setYaxisShowForNullSeries(bool $yaxisShowForNullSeries): Chart
    {
        $this->yaxisShowForNullSeries = $yaxisShowForNullSeries;

        $this->setOption([
            'yaxis' => [
                'showForNullSeries' => $yaxisShowForNullSeries,
            ],
        ]);

        return $this;
    }

    public function getYaxisShowForNullSeries(): bool
    {
        return $this->yaxisShowForNullSeries;
    }

    public function setYaxisSeriesName(string $yaxisSeriesName): Chart
    {
        $this->yaxisSeriesName = $yaxisSeriesName;

        $this->setOption([
            'yaxis' => [
                'seriesName' => $yaxisSeriesName,
            ],
        ]);

        return $this;
    }

    public function getYaxisSeriesName(): string
    {
        return $this->yaxisSeriesName;
    }

    public function setYaxisOpposite(bool $yaxisOpposite): Chart
    {
        $this->yaxisOpposite = $yaxisOpposite;

        $this->setOption([
            'yaxis' => [
                'opposite' => $yaxisOpposite,
            ],
        ]);

        return $this;
    }

    public function getYaxisOpposite(): bool
    {
        return $this->yaxisOpposite;
    }

    public function setYaxisReversed(bool $yaxisReversed): Chart
    {
        $this->yaxisReversed = $yaxisReversed;

        $this->setOption([
            'yaxis' => [
                'reversed' => $yaxisReversed,
            ],
        ]);

        return $this;
    }

    public function getYaxisReversed(): bool
    {
        return $this->yaxisReversed;
    }

    public function setYaxisLogarithmic(bool $yaxisLogarithmic): Chart
    {
        $this->yaxisLogarithmic = $yaxisLogarithmic;

        $this->setOption([
            'yaxis' => [
                'logarithmic' => $yaxisLogarithmic,
            ],
        ]);

        return $this;
    }

    public function getYaxisLogarithmic(): bool
    {
        return $this->yaxisLogarithmic;
    }

    public function setYaxisLogBase(int $yaxisLogBase): Chart
    {
        $this->yaxisLogBase = $yaxisLogBase;

        $this->setOption([
            'yaxis' => [
                'logBase' => $yaxisLogBase,
            ],
        ]);

        return $this;
    }

    public function getYaxisLogBase(): int
    {
        return $this->yaxisLogBase;
    }

    public function setYaxisTickAmount(int $yaxisTickAmount): Chart
    {
        $this->yaxisTickAmount = $yaxisTickAmount;

        $this->setOption([
            'yaxis' => [
                'tickAmount' => $yaxisTickAmount,
            ],
        ]);

        return $this;
    }

    public function getYaxisTickAmount(): int
    {
        return $this->yaxisTickAmount;
    }

    public function setYaxisMin(int $yaxisMin): Chart
    {
        $this->yaxisMin = $yaxisMin;

        $this->setOption([
            'yaxis' => [
                'min' => $yaxisMin,
            ],
        ]);

        return $this;
    }

    public function getYaxisMin(): int
    {
        return $this->yaxisMin;
    }

    public function setYaxisMax(int $yaxisMax): Chart
    {
        $this->yaxisMax = $yaxisMax;

        $this->setOption([
            'yaxis' => [
                'max' => $yaxisMax,
            ],
        ]);

        return $this;
    }

    public function getYaxisMax(): int
    {
        return $this->yaxisMax;
    }

    public function setYaxisForceNiceScale(bool $yaxisForceNiceScale): Chart
    {
        $this->yaxisForceNiceScale = $yaxisForceNiceScale;

        $this->setOption([
            'yaxis' => [
                'forceNiceScale' => $yaxisForceNiceScale,
            ],
        ]);

        return $this;
    }

    public function getYaxisForceNiceScale(): bool
    {
        return $this->yaxisForceNiceScale;
    }

    public function setYaxisFloating(bool $yaxisFloating): Chart
    {
        $this->yaxisFloating = $yaxisFloating;

        $this->setOption([
            'yaxis' => [
                'floating' => $yaxisFloating,
            ],
        ]);

        return $this;
    }

    public function getYaxisFloating(): bool
    {
        return $this->yaxisFloating;
    }

    public function setYaxisDecimalsInFloat(int $yaxisDecimalsInFloat): Chart
    {
        $this->yaxisDecimalsInFloat = $yaxisDecimalsInFloat;

        $this->setOption([
            'yaxis' => [
                'decimalsInFloat' => $yaxisDecimalsInFloat,
            ],
        ]);

        return $this;
    }

    public function getYaxisDecimalsInFloat(): int
    {
        return $this->yaxisDecimalsInFloat;
    }

    public function setYaxisLabels(array $yaxisLabels): Chart
    {
        $this->yaxisLabels = $yaxisLabels;

        $this->setOption([
            'yaxis' => [
                'labels' => $yaxisLabels,
            ],
        ]);

        return $this;
    }

    public function getYaxisLabels(): array
    {
        return $this->yaxisLabels;
    }

    public function setYaxisAxisBorder(array $yaxisAxisBorder): Chart
    {
        $this->yaxisAxisBorder = $yaxisAxisBorder;

        $this->setOption([
            'yaxis' => [
                'axisBorder' => $yaxisAxisBorder,
            ],
        ]);

        return $this;
    }

    public function getYaxisAxisBorder(): array
    {
        return $this->yaxisAxisBorder;
    }

    public function setYaxisAxisTicks(array $yaxisAxisTicks): Chart
    {
        $this->yaxisAxisTicks = $yaxisAxisTicks;

        $this->setOption([
            'yaxis' => [
                'axisTicks' => $yaxisAxisTicks,
            ],
        ]);

        return $this;
    }

    public function getYaxisAxisTicks(): array
    {
        return $this->yaxisAxisTicks;
    }

    public function setYaxisTitle(array $yaxisTitle): Chart
    {
        $this->yaxisTitle = $yaxisTitle;

        $this->setOption([
            'yaxis' => [
                'title' => $yaxisTitle,
            ],
        ]);

        return $this;
    }

    public function getYaxisTitle(): array
    {
        return $this->yaxisTitle;
    }

    public function setYaxisCrosshairs(array $yaxisCrosshairs): Chart
    {
        $this->yaxisCrosshairs = $yaxisCrosshairs;

        $this->setOption([
            'yaxis' => [
                'crosshairs' => $yaxisCrosshairs,
            ],
        ]);

        return $this;
    }

    public function getYaxisCrosshairs(): array
    {
        return $this->yaxisCrosshairs;
    }

    public function setYaxisTooltip(array $yaxisTooltip): Chart
    {
        $this->yaxisTooltip = $yaxisTooltip;

        $this->setOption([
            'yaxis' => [
                'tooltip' => $yaxisTooltip,
            ],
        ]);

        return $this;
    }

    public function getYaxisTooltip(): array
    {
        return $this->yaxisTooltip;
    }
}
