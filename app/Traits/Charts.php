<?php

namespace App\Traits;

use Akaunting\Apexcharts\Charts as Apexcharts;

trait Charts
{
    public $bar = [
        'colors' => [],
        'labels' => [],
        'values' => [],
    ];

    public $donut = [
        'colors' => [],
        'labels' => [],
        'values' => [],
    ];

    public function addToDonut($color, $label, $value)
    {
        $this->donut['colors'][] = $color;
        $this->donut['labels'][] = $label;
        $this->donut['values'][] = (int) $value;
    }

    public function addMoneyToDonut($color, $amount, $description = '')
    {
        $label = money($amount, setting('default.currency'), true)->format();

        if (!empty($description)) {
            $label .= ' - ' . $description;
        }

        $this->addToDonut($color, $label, $amount);
    }

    public function getDonutChart($name, $width = '100%', $height = 300, $limit = 10)
    {
        // Show donut prorated if there is no value
        if (array_sum($this->donut['values']) == 0) {
            foreach ($this->donut['values'] as $key => $value) {
                $this->donut['values'][$key] = 1;
            }
        }

        // Get 6 categories by amount
        $colors = $labels = [];
        $values = collect($this->donut['values'])->sort()->reverse()->take($limit)->all();

        foreach ($values as $id => $val) {
            $colors[$id] = $this->donut['colors'][$id];
            $labels[$id] = $this->donut['labels'][$id];
        }

        $chart = new Apexcharts();

        $chart->setType('donut')
            ->setWidth($width)
            ->setHeight($height)
            ->setLabels(array_values($labels))
            ->setColors(array_values($colors))
            ->setDataset($name, 'donut', array_values($values));

        return $chart;
    }

    public function addToBar($color, $label, $value)
    {
        $this->bar['colors'][] = $color;
        $this->bar['labels'][] = $label;
        $this->bar['values'][] = (int) $value;
    }

    public function getBarChart($name, $width = '100%', $height = 160)
    {
        $chart = new Apexcharts();

        $chart->setType('bar')
            ->setWidth($width)
            ->setHeight($height)
            ->setLabels(array_values($this->bar['labels']))
            ->setColors($this->bar['colors']);

        foreach ($this->bar['values'] as $key => $value) {
            $chart->setDataset($this->bar['labels'][$key], 'bar', $value);
        }

        return $chart;
    }
}
