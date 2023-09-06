<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait NoData
{
    public string $noDataText = '';

    public string $noDataAlign = 'center';

    public string $noDataVerticalAlign = 'middle';

    public int $noDataOffsetX = 0;

    public int $noDataOffsetY = 0;

    public array $noDataStyle = [];

    public function setNoDataText(string $noDataText): Chart
    {
        $this->noDataText = $noDataText;

        $this->setOption([
            'noData' => [
                'text' => $noDataText,
            ],
        ]);

        return $this;
    }

    public function getNoDataText(): string
    {
        return $this->noDataText;
    }

    public function setNoDataAlign(string $noDataAlign): Chart
    {
        $this->noDataAlign = $noDataAlign;

        $this->setOption([
            'noData' => [
                'align' => $noDataAlign,
            ],
        ]);

        return $this;
    }

    public function getNoDataAlign(): string
    {
        return $this->noDataAlign;
    }

    public function setNoDataVerticalAlign(string $noDataVerticalAlign): Chart
    {
        $this->noDataVerticalAlign = $noDataVerticalAlign;

        $this->setOption([
            'noData' => [
                'verticalAlign' => $noDataVerticalAlign,
            ],
        ]);

        return $this;
    }

    public function getNoDataVerticalAlign(): string
    {
        return $this->noDataVerticalAlign;
    }

    public function setNoDataOffsetX(int $noDataOffsetX): Chart
    {
        $this->noDataOffsetX = $noDataOffsetX;

        $this->setOption([
            'noData' => [
                'offsetX' => $noDataOffsetX,
            ],
        ]);

        return $this;
    }

    public function getNoDataOffsetX(): int
    {
        return $this->noDataOffsetX;
    }

    public function setNoDataOffsetY(int $noDataOffsetY): Chart
    {
        $this->noDataOffsetY = $noDataOffsetY;

        $this->setOption([
            'noData' => [
                'offsetY' => $noDataOffsetY,
            ],
        ]);

        return $this;
    }

    public function getNoDataOffsetY(): int
    {
        return $this->noDataOffsetY;
    }

    public function setNoDataStyle(array $noDataStyle): Chart
    {
        $this->noDataStyle = $noDataStyle;

        $this->setOption([
            'noData' => [
                'style' => $noDataStyle,
            ],
        ]);

        return $this;
    }

    public function getNoDataStyle(): array
    {
        return $this->noDataStyle;
    }
}
