<?php

namespace Akaunting\Apexcharts\Types;

use Akaunting\Apexcharts\Chart;
use Illuminate\Support\Collection;

class Radar extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('radar');
    }

    public function addArea(string $name, array|Collection $data): Radar
    {
        $type = $this->getType();

        return $this->setDataset($name, $type, $data);
    }
}
