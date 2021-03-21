<?php

namespace ConsoleTVs\Charts\Features\Fusioncharts;

use Illuminate\Support\Collection;

trait Dataset
{
    /**
     * Set the dataset color.
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
            'color' => $color,
        ]);
    }
}
