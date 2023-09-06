<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Xaxis
{
    public string $xaxisType = 'category';

    public array $xaxisCategories = [];

    public int $xaxisTickAmount = 6;

    public string $xaxisTickPlacement = 'between';

    public int $xaxisMin;

    public int $xaxisMax;

    public int $xaxisRange;

    public bool $xaxisFloating = false;

    public int $xaxisDecimalsInFloat;

    public array $xaxisOverwriteCategories;

    public string $xaxisPosition = 'bottom';

    public array $xaxisLabels = [];

    public array $xaxisAxisBorder = [];

    public array $xaxisAxisTicks = [];

    public array $xaxisTitle = [];

    public array $xaxisCrosshairs = [];

    public array $xaxisTooltip = [];

    public function setXaxisType(string $xaxisType): Chart
    {
        $this->xaxisType = $xaxisType;

        $this->setOption([
            'xaxis' => [
                'type' => $xaxisType,
            ],
        ]);

        return $this;
    }

    public function getXaxisType(): string
    {
        return $this->xaxisType;
    }

    public function setXaxisCategories(array $xaxisCategories): Chart
    {
        $this->xaxisCategories = $xaxisCategories;

        $this->setOption([
            'xaxis' => [
                'categories' => $xaxisCategories,
            ],
        ]);

        return $this;
    }

    public function getXaxisCategories(): array
    {
        return $this->xaxisCategories;
    }

    public function setXaxisTickAmount(int $xaxisTickAmount): Chart
    {
        $this->xaxisTickAmount = $xaxisTickAmount;

        $this->setOption([
            'xaxis' => [
                'tickAmount' => $xaxisTickAmount,
            ],
        ]);

        return $this;
    }

    public function getXaxisTickAmount(): int
    {
        return $this->xaxisTickAmount;
    }

    public function setXaxisTickPlacement(string $xaxisTickPlacement): Chart
    {
        $this->xaxisTickPlacement = $xaxisTickPlacement;

        $this->setOption([
            'xaxis' => [
                'tickPlacement' => $xaxisTickPlacement,
            ],
        ]);

        return $this;
    }

    public function getXaxisTickPlacement(): string
    {
        return $this->xaxisTickPlacement;
    }

    public function setXaxisMin(int $xaxisMin): Chart
    {
        $this->xaxisMin = $xaxisMin;

        $this->setOption([
            'xaxis' => [
                'min' => $xaxisMin,
            ],
        ]);

        return $this;
    }

    public function getXaxisMin(): int
    {
        return $this->xaxisMin;
    }

    public function setXaxisMax(int $xaxisMax): Chart
    {
        $this->xaxisMax = $xaxisMax;

        $this->setOption([
            'xaxis' => [
                'max' => $xaxisMax,
            ],
        ]);

        return $this;
    }

    public function getXaxisMax(): int
    {
        return $this->xaxisMax;
    }

    public function setXaxisRange(int $xaxisRange): Chart
    {
        $this->xaxisRange = $xaxisRange;

        $this->setOption([
            'xaxis' => [
                'range' => $xaxisRange,
            ],
        ]);

        return $this;
    }

    public function getXaxisRange(): int
    {
        return $this->xaxisRange;
    }

    public function setXaxisFloating(bool $xaxisFloating): Chart
    {
        $this->xaxisFloating = $xaxisFloating;

        $this->setOption([
            'xaxis' => [
                'floating' => $xaxisFloating,
            ],
        ]);

        return $this;
    }

    public function getXaxisFloating(): bool
    {
        return $this->xaxisFloating;
    }

    public function setXaxisDecimalsInFloat(int $xaxisDecimalsInFloat): Chart
    {
        $this->xaxisDecimalsInFloat = $xaxisDecimalsInFloat;

        $this->setOption([
            'xaxis' => [
                'decimalsInFloat' => $xaxisDecimalsInFloat,
            ],
        ]);

        return $this;
    }

    public function getXaxisDecimalsInFloat(): int
    {
        return $this->xaxisDecimalsInFloat;
    }

    public function setXaxisOverwriteCategories(array $xaxisOverwriteCategories): Chart
    {
        $this->xaxisOverwriteCategories = $xaxisOverwriteCategories;

        $this->setOption([
            'xaxis' => [
                'overwriteCategories' => $xaxisOverwriteCategories,
            ],
        ]);

        return $this;
    }

    public function getXaxisOverwriteCategories(): array
    {
        return $this->xaxisOverwriteCategories;
    }

    public function setXaxisPosition(string $xaxisPosition): Chart
    {
        $this->xaxisPosition = $xaxisPosition;

        $this->setOption([
            'xaxis' => [
                'position' => $xaxisPosition,
            ],
        ]);

        return $this;
    }

    public function getXaxisPosition(): string
    {
        return $this->xaxisPosition;
    }

    public function setXaxisLabels(array $xaxisLabels): Chart
    {
        $this->xaxisLabels = $xaxisLabels;

        $this->setOption([
            'xaxis' => [
                'labels' => $xaxisLabels,
            ],
        ]);

        return $this;
    }

    public function getXaxisLabels(): array
    {
        return $this->xaxisLabels;
    }

    public function setXaxisAxisBorder(array $xaxisAxisBorder): Chart
    {
        $this->xaxisAxisBorder = $xaxisAxisBorder;

        $this->setOption([
            'xaxis' => [
                'axisBorder' => $xaxisAxisBorder,
            ],
        ]);

        return $this;
    }

    public function getXaxisAxisBorder(): array
    {
        return $this->xaxisAxisBorder;
    }

    public function setXaxisAxisTicks(array $xaxisAxisTicks): Chart
    {
        $this->xaxisAxisTicks = $xaxisAxisTicks;

        $this->setOption([
            'xaxis' => [
                'axisTicks' => $xaxisAxisTicks,
            ],
        ]);

        return $this;
    }

    public function getXaxisAxisTicks(): array
    {
        return $this->xaxisAxisTicks;
    }

    public function setXaxisTitle(array $xaxisTitle): Chart
    {
        $this->xaxisTitle = $xaxisTitle;

        $this->setOption([
            'xaxis' => [
                'title' => $xaxisTitle,
            ],
        ]);

        return $this;
    }

    public function getXaxisTitle(): array
    {
        return $this->xaxisTitle;
    }

    public function setXaxisCrosshairs(array $xaxisCrosshairs): Chart
    {
        $this->xaxisCrosshairs = $xaxisCrosshairs;

        $this->setOption([
            'xaxis' => [
                'crosshairs' => $xaxisCrosshairs,
            ],
        ]);

        return $this;
    }

    public function getXaxisCrosshairs(): array
    {
        return $this->xaxisCrosshairs;
    }

    public function setXaxisTooltip(array $xaxisTooltip): Chart
    {
        $this->xaxisTooltip = $xaxisTooltip;

        $this->setOption([
            'xaxis' => [
                'tooltip' => $xaxisTooltip,
            ],
        ]);

        return $this;
    }

    public function getXaxisTooltip(): array
    {
        return $this->xaxisTooltip;
    }
}
