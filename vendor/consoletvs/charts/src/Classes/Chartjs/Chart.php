<?php

namespace ConsoleTVs\Charts\Classes\Chartjs;

use ConsoleTVs\Charts\Classes\BaseChart;
use ConsoleTVs\Charts\Features\Chartjs\Chart as ChartFeatures;

class Chart extends BaseChart
{
    use ChartFeatures;

    /**
     * Chartjs dataset class.
     *
     * @var object
     */
    public $dataset = Dataset::class;

    /**
     * Initiates the Chartjs Line Chart.
     *
     * @return self
     */
    public function __construct()
    {
        parent::__construct();

        $this->container = 'charts::chartjs.container';
        $this->script = 'charts::chartjs.script';

        return $this->options([
            'maintainAspectRatio' => false,
            'scales'              => [
                'xAxes' => [],
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
