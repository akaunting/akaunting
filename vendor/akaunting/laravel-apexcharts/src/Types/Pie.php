<?php

namespace Akaunting\Apexcharts\Types;

use Akaunting\Apexcharts\Chart;

class Pie extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('pie');
    }

    public function addPieces(array $data): Pie
    {
        return $this->setSeries($data);
    }
}
