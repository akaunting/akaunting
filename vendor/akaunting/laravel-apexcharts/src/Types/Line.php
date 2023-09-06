<?php

namespace Akaunting\Apexcharts\Types;

use Akaunting\Apexcharts\Chart;
use Illuminate\Support\Collection;

class Line extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('line');
    }

    public function addLine(string $name, array|Collection $data): Line
    {
        $type = $this->getType();

        return $this->setDataset($name, $type, $data);
    }
}
