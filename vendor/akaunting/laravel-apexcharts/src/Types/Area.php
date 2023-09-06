<?php

namespace Akaunting\Apexcharts\Types;

use Akaunting\Apexcharts\Chart;
use Illuminate\Support\Collection;

class Area extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('area');
    }

    public function addArea(string $name, array|Collection $data): Area
    {
        $type = $this->getType();

        return $this->setDataset($name, $type, $data);
    }
}
