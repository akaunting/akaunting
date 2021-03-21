<?php

namespace ConsoleTVs\Charts\Features\Frappe;

trait Dataset
{
    /**
     * Determines the color of the dataset.
     *
     * @param string $color
     *
     * @return self
     */
    public function color(string $color)
    {
        return $this->options([
            'color' => $color,
        ]);
    }
}
