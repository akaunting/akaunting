<?php

namespace ConsoleTVs\Charts\Features\Echarts;

use Illuminate\Support\Collection;

trait Dataset
{
    /**
     * Set the dataset color.
     *
     * @param string|array|Collection $color
     *
     * @return void
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
