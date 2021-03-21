<?php

namespace ConsoleTVs\Charts\Features\Highcharts;

trait Chart
{
    /**
     * Display the chart axes.
     *
     * @param bool $axes
     *
     * @return self
     */
    public function displayAxes(bool $axes)
    {
        return $this->options([
            'xAxis' => [
                'visible' => $axes,
            ],
            'yAxis' => [
                'visible' => $axes,
            ],
        ]);
    }

    /**
     * Display the legend.
     *
     * @param bool $legend
     *
     * @return self
     */
    public function displayLegend(bool $legend)
    {
        return $this->options([
            'legend' => [
                'enabled' => $legend,
            ],
        ]);
    }

    /**
     * Rotates the labels of the xAxis.
     *
     * @param float $angle
     *
     * @return self
     */
    public function labelsRotation(float $angle)
    {
        return $this->options([
            'xAxis' => [
                'labels' => [
                    'rotation' => $angle,
                ],
            ],
        ]);
    }

    /**
     * Set the chart style to minimalist.
     *
     * @param bool $display
     *
     * @return self
     */
    public function minimalist(bool $display = false)
    {
        $this->displayLegend(!$display);

        return $this->displayAxes(!$display);
    }

    /**
     * Set the highcharts yAxis label.
     *
     * @param string $label
     *
     * @return self
     */
    public function label(string $label)
    {
        return $this->options([
            'yAxis' => [
                'title' => [
                    'text' => $label,
                ],
            ],
        ]);
    }

    /**
     * Set the chart title.
     *
     * @param string $title
     *
     * @return self
     */
    public function title(string $title)
    {
        return $this->options([
            'title' => [
                'text' => $title,
            ],
        ]);
    }

    /**
     * Shapes the pie chart into a doughnut.
     *
     * @return self
     */
    public function doughnut(int $size = 50)
    {
        return $this->options([
            'plotOptions' => [
                'pie' => [
                    'innerSize' => "{$size}%",
                ],
            ],
        ]);
    }
}
