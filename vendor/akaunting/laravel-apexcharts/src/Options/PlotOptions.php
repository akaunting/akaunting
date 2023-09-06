<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait PlotOptions
{
    public string $area = 'origin';

    public array $bar = [
        'horizontal' => false,
    ];

    public array $bubble = [];

    public array $candlestick = [];

    public array $boxPlot = [];

    public array $heatmap = [];

    public array $treemap = [];

    public array $pie = [];

    public array $polarArea = [];

    public array $radar = [];

    public array $radialBar = [];

    public function setArea(string $area): Chart
    {
        $this->area = $area;

        $this->setOption([
            'plotOptions' => [
                'area' => $area,
            ],
        ]);

        return $this;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function setBar(array $bar): Chart
    {
        $this->bar = $bar;

        $this->setOption([
            'plotOptions' => [
                'bar' => $bar,
            ],
        ]);

        return $this;
    }

    public function getBar(): array
    {
        return $this->bar;
    }

    public function setBubble(array $bubble): Chart
    {
        $this->bubble = $bubble;

        $this->setOption([
            'plotOptions' => [
                'bubble' => $bubble,
            ],
        ]);

        return $this;
    }

    public function getBubble(): array
    {
        return $this->bubble;
    }

    public function setCandlestick(array $candlestick): Chart
    {
        $this->candlestick = $candlestick;

        $this->setOption([
            'plotOptions' => [
                'candlestick' => $candlestick,
            ],
        ]);

        return $this;
    }

    public function getCandlestick(): array
    {
        return $this->candlestick;
    }

    public function setBoxPlot(array $boxPlot): Chart
    {
        $this->boxPlot = $boxPlot;

        $this->setOption([
            'plotOptions' => [
                'boxPlot' => $boxPlot,
            ],
        ]);

        return $this;
    }

    public function getBoxPlot(): array
    {
        return $this->boxPlot;
    }

    public function setHeatmap(array $heatmap): Chart
    {
        $this->heatmap = $heatmap;

        $this->setOption([
            'plotOptions' => [
                'heatmap' => $heatmap,
            ],
        ]);

        return $this;
    }

    public function getHeatmap(): array
    {
        return $this->heatmap;
    }

    public function setTreemap(array $treemap): Chart
    {
        $this->treemap = $treemap;

        $this->setOption([
            'plotOptions' => [
                'treemap' => $treemap,
            ],
        ]);

        return $this;
    }

    public function getTreemap(): array
    {
        return $this->treemap;
    }

    public function setPie(array $pie): Chart
    {
        $this->pie = $pie;

        $this->setOption([
            'plotOptions' => [
                'pie' => $pie,
            ],
        ]);

        return $this;
    }

    public function getPie(): array
    {
        return $this->pie;
    }

    public function setPolarArea(array $polarArea): Chart
    {
        $this->polarArea = $polarArea;

        $this->setOption([
            'plotOptions' => [
                'polarArea' => $polarArea,
            ],
        ]);

        return $this;
    }

    public function getPolarArea(): array
    {
        return $this->polarArea;
    }

    public function setRadar(array $radar): Chart
    {
        $this->radar = $radar;

        $this->setOption([
            'plotOptions' => [
                'radar' => $radar,
            ],
        ]);

        return $this;
    }

    public function getRadar(): array
    {
        return $this->radar;
    }

    public function setRadialBar(array $radialBar): Chart
    {
        $this->radialBar = $radialBar;

        $this->setOption([
            'plotOptions' => [
                'radialBar' => $radialBar,
            ],
        ]);

        return $this;
    }

    public function getRadialBar(): array
    {
        return $this->radialBar;
    }

    public function setHorizontal(bool $horizontal): Chart
    {
        $this->setBar([
            'horizontal' => $horizontal,
        ]);

        return $this;
    }

    public function getHorizontal(): bool
    {
        return $this->getBar()['horizontal'];
    }
}
