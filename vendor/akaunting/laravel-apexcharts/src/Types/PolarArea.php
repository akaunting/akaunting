<?php

namespace Akaunting\Apexcharts\Types;

use Akaunting\Apexcharts\Chart;

class PolarArea extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('polarArea');
    }

    public function addPolarAreas(array $data): PolarArea
    {
        return $this->setSeries($data);
    }
}
