<?php

namespace Akaunting\Apexcharts\Traits;

use Akaunting\Apexcharts\Types\Area;
use Akaunting\Apexcharts\Types\Bar;
use Akaunting\Apexcharts\Types\Donut;
use Akaunting\Apexcharts\Types\HeatMap;
use Akaunting\Apexcharts\Types\HorizontalBar;
use Akaunting\Apexcharts\Types\Line;
use Akaunting\Apexcharts\Types\Pie;
use Akaunting\Apexcharts\Types\PolarArea;
use Akaunting\Apexcharts\Types\Radar;
use Akaunting\Apexcharts\Types\Radial;

trait Types
{
    public function area(): Area
    {
        return new Area();
    }

    public function bar(): Bar
    {
        return new Bar();
    }

    public function donut(): Donut
    {
        return new Donut();
    }

    public function heatMap(): HeatMap
    {
        return new HeatMap();
    }

    public function horizontalBar(): HorizontalBar
    {
        return new HorizontalBar();
    }

    public function line(): Line
    {
        return new Line();
    }

    public function pie(): Pie
    {
        return new Pie();
    }

    public function polarArea(): PolarArea
    {
        return new PolarArea();
    }

    public function radar(): Radar
    {
        return new Radar();
    }

    public function radial(): Radial
    {
        return new Radial();
    }
}
