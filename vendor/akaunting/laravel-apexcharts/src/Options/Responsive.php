<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Responsive
{
    public int $responsiveBreakpoint;

    public object $responsiveOptions;

    public function setResponsiveBreakpoint(int $responsiveBreakpoint): Chart
    {
        $this->responsiveBreakpoint = $responsiveBreakpoint;

        $this->setOption([
            'responsive' => [
                'breakpoint' => $responsiveBreakpoint,
            ],
        ]);

        return $this;
    }

    public function getResponsiveBreakpoint(): int
    {
        return $this->responsiveBreakpoint;
    }

    public function setResponsiveOptions(object $responsiveOptions): Chart
    {
        $this->responsiveOptions = $responsiveOptions;

        $this->setOption([
            'responsive' => [
                'options' => $responsiveOptions,
            ],
        ]);

        return $this;
    }

    public function getResponsiveOptions(): object
    {
        return $this->responsiveOptions;
    }
}
