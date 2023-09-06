<?php

namespace Akaunting\Apexcharts\Types;

use Akaunting\Apexcharts\Chart;
use Illuminate\Support\Collection;

class Bar extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('bar');
    }

    public function addBar(string $name, array|Collection $data): Bar
    {
        $type = $this->getType();

        return $this->setDataset($name, $type, $data);
    }
}
