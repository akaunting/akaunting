<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait States
{
    public array $statesNormal;

    public array $statesHover;

    public array $statesActive;

    public function setStatesNormal(array $statesNormal): Chart
    {
        $this->statesNormal = $statesNormal;

        $this->setOption([
            'states' => [
                'normal' => $statesNormal,
            ],
        ]);

        return $this;
    }

    public function getStatesNormal(): array
    {
        return $this->statesNormal;
    }

    public function setStatesHover(array $statesHover): Chart
    {
        $this->statesHover = $statesHover;

        $this->setOption([
            'states' => [
                'hover' => $statesHover,
            ],
        ]);

        return $this;
    }

    public function getStatesHover(): array
    {
        return $this->statesHover;
    }

    public function setStatesActive(array $statesActive): Chart
    {
        $this->statesActive = $statesActive;

        $this->setOption([
            'states' => [
                'active' => $statesActive,
            ],
        ]);

        return $this;
    }

    public function getStatesActive(): array
    {
        return $this->statesActive;
    }
}
