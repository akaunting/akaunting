<?php

namespace ConsoleTVs\Charts\Features\Chartjs;

trait Chart
{
    /**
     * Minalist chart display (Hide labels and axes).
     *
     * @return self
     */
    public function minimalist(bool $display)
    {
        $this->displayLegend(!$display);

        return $this->displayAxes(!$display);
    }

    /**
     * Display the chart legend.
     *
     * @param bool $legend
     *
     * @return self
     */
    public function displayLegend(bool $legend)
    {
        return $this->options([
            'legend' => [
                'display' => $legend,
            ],
        ]);
    }

    /**
     * Display the chart axis.
     *
     * @param bool $axes
     *
     * @return self
     */
    public function displayAxes(bool $axes, bool $strict = false)
    {
        if ($strict) {
            return $this->options([
                'scale' => [
                    'display' => $axes,
                ],
            ]);
        }

        return $this->options([
            'scales' => [
                'xAxes' => [
                    [
                        'display' => $axes,
                    ],
                ],
                'yAxes' => [
                    [
                        'display' => $axes,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Set the bar width of the X Axis.
     *
     * @param float $width
     *
     * @return self
     */
    public function barWidth(float $width)
    {
        return $this->options([
            'scales' => [
                'xAxes' => [
                    [
                        'barPercentage' => $width,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Set the chart title.
     *
     * @param string $title
     * @param int    $font_size
     * @param string $color
     * @param string $font_weight
     * @param string $font_family
     *
     * @return self
     */
    public function title(
        string $title,
        int $font_size = 14,
        string $color = '#666',
        string $font_weight = 'bold',
        string $font_family = "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
    ) {
        return $this->options([
            'title' => [
                'display'    => true,
                'fontFamily' => $font_family,
                'fontSize'   => $font_size,
                'fontColor'  => $color,
                'fontStyle'  => $font_weight,
                'text'       => $title,
            ],
        ]);
    }
}
