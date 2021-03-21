<?php

namespace ConsoleTVs\Charts\Features\Chartjs;

use Illuminate\Support\Collection;

trait Dataset
{
    /**
     * Set the dataset border color.
     *
     * @param string|array|Collection $color
     *
     * @return self
     */
    public function color($color)
    {
        if ($color instanceof Collection) {
            $color = $color->toArray();
        }

        return $this->options([
            'borderColor' => $color,
        ]);
    }

    /**
     * Set the dataset background color.
     *
     * @param string|array|Collection $color
     *
     * @return self
     */
    public function backgroundColor($color)
    {
        if ($color instanceof Collection) {
            $color = $color->toArray();
        }

        return $this->options([
            'backgroundColor' => $color,
        ]);
    }

    /**
     * Determines if the dataset is filled.
     *
     * @param bool $filled
     *
     * @return self
     */
    public function fill(bool $filled)
    {
        return $this->options([
            'fill' => $filled,
        ]);
    }

    /**
     * Set the chart line tension.
     *
     * @param int $tension
     *
     * @return self
     */
    public function lineTension(float $tension)
    {
        return $this->options([
            'lineTension' => $tension,
        ]);
    }

    /**
     * Set the line to a dashed line in the chart options.
     *
     * @param array $dashed
     *
     * @return self
     */
    public function dashed(array $dashed = [5])
    {
        return $this->options([
            'borderDash' => $dashed,
        ]);
    }

    /**
     * Set the label of the dataset.
     *
     * @param $label string
     *
     * @return self
     */
    public function label($label)
    {
        return $this->options([
            'label' => $label,
        ]);
    }
}
