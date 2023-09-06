<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Grid
{
    public bool $gridShow = true;

    public string $gridBorderColor = '#90A4AE';

    public int $gridStrokeDashArray = 0;

    public string $gridPosition = 'back';

    public array $gridXaxis = [];

    public array $gridYaxis = [];

    public array $gridRow = [];

    public array $gridColumn = [];

    public array $gridPadding = [];

    public function setGridShow(bool $gridShow): Chart
    {
        $this->gridShow = $gridShow;

        $this->setOption([
            'grid' => [
                'show' => $gridShow,
            ],
        ]);

        return $this;
    }

    public function getGridShow(): bool
    {
        return $this->gridShow;
    }

    public function setGridBorderColor(string $gridBorderColor): Chart
    {
        $this->gridBorderColor = $gridBorderColor;

        $this->setOption([
            'grid' => [
                'borderColor' => $gridBorderColor,
            ],
        ]);

        return $this;
    }

    public function getGridBorderColor(): string
    {
        return $this->gridBorderColor;
    }

    public function setGridStrokeDashArray(int $gridStrokeDashArray): Chart
    {
        $this->gridStrokeDashArray = $gridStrokeDashArray;

        $this->setOption([
            'grid' => [
                'strokeDashArray' => $gridStrokeDashArray,
            ],
        ]);

        return $this;
    }

    public function getGridStrokeDashArray(): int
    {
        return $this->gridStrokeDashArray;
    }

    public function setGridPosition(string $gridPosition): Chart
    {
        $this->gridPosition = $gridPosition;

        $this->setOption([
            'grid' => [
                'position' => $gridPosition,
            ],
        ]);

        return $this;
    }

    public function getGridPosition(): string
    {
        return $this->gridPosition;
    }

    public function setGridXaxis(array $gridXaxis): Chart
    {
        $this->gridXaxis = $gridXaxis;

        $this->setOption([
            'grid' => [
                'xaxis' => $gridXaxis,
            ],
        ]);

        return $this;
    }

    public function getGridXaxis(): array
    {
        return $this->gridXaxis;
    }

    public function setGridYaxis(array $gridYaxis): Chart
    {
        $this->gridYaxis = $gridYaxis;

        $this->setOption([
            'grid' => [
                'yaxis' => $gridYaxis,
            ],
        ]);

        return $this;
    }

    public function getGridYaxis(): array
    {
        return $this->gridYaxis;
    }

    public function setGridRow(array $gridRow): Chart
    {
        $this->gridRow = $gridRow;

        $this->setOption([
            'grid' => [
                'row' => $gridRow,
            ],
        ]);

        return $this;
    }

    public function getGridRow():array
    {
        return $this->gridRow;
    }

    public function setGridColumn(array $gridColumn): Chart
    {
        $this->gridColumn = $gridColumn;

        $this->setOption([
            'grid' => [
                'column' => $gridColumn,
            ],
        ]);

        return $this;
    }

    public function getGridColumn(): array
    {
        return $this->gridColumn;
    }

    public function setGridPadding(array $gridPadding): Chart
    {
        $this->gridPadding = $gridPadding;

        $this->setOption([
            'grid' => [
                'padding' => $gridPadding,
            ],
        ]);

        return $this;
    }

    public function getGridPadding(): array
    {
        return $this->gridPadding;
    }
}
