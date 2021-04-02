<?php

namespace App\Traits;

use App\Utilities\Chartjs;

trait Charts
{
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

    public function getDonutChart($name, $width = 0, $height = 160, $limit = 10)
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

        $chart = new Chartjs();

        $chart->type('doughnut')
            ->width($width)
            ->height($height)
            ->options($this->getDonutChartOptions($colors))
            ->labels(array_values($labels));

        $chart->dataset($name, 'doughnut', array_values($values))
        ->backgroundColor(array_values($colors));

        return $chart;
    }

    public function getDonutChartOptions($colors)
    {
        return [
            'color' => array_values($colors),
            'cutoutPercentage' => 80,
            'legend' => [
                'position' => 'right',
            ],
            'tooltips' => [
                'backgroundColor' => '#000000',
                'titleFontColor' => '#ffffff',
                'bodyFontColor' => '#e5e5e5',
                'bodySpacing' => 4,
                'xPadding' => 12,
                'mode' => 'nearest',
                'intersect' => 0,
                'position' => 'nearest',
            ],
            'scales' => [
                'yAxes' => [
                    'display' => false,
                ],
                'xAxes' => [
                    'display' => false,
                ],
            ],
        ];
    }

    public function getLineChartOptions()
    {
        return [
            'tooltips' => [
                'backgroundColor' => '#000000',
                'titleFontColor' => '#ffffff',
                'bodyFontColor' => '#e5e5e5',
                'bodySpacing' => 4,
                'YrPadding' => 12,
                'mode' => 'nearest',
                'intersect' => 0,
                'position' => 'nearest',
            ],
            'responsive' => true,
            'scales' => [
                'yAxes' => [[
                    'barPercentage' => 1.6,
                    'ticks' => [
                        'padding' => 10,
                        'fontColor' => '#9e9e9e',
                    ],
                    'gridLines' => [
                        'drawBorder' => false,
                        'color' => 'rgba(29,140,248,0.1)',
                        'zeroLineColor' => 'transparent',
                        'borderDash' => [2],
                        'borderDashOffset' => [2],
                    ],
                ]],
                'xAxes' => [[
                    'barPercentage' => 1.6,
                    'ticks' => [
                        'suggestedMin' => 60,
                        'suggestedMax' => 125,
                        'padding' => 20,
                        'fontColor' => '#9e9e9e',
                    ],
                    'gridLines' => [
                        'drawBorder' => false,
                        'color' => 'rgba(29,140,248,0.0)',
                        'zeroLineColor' => 'transparent',
                    ],
                ]],
            ],
        ];
    }
}
