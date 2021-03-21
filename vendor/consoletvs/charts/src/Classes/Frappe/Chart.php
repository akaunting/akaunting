<?php

namespace ConsoleTVs\Charts\Classes\Frappe;

use ConsoleTVs\Charts\Classes\BaseChart;
use ConsoleTVs\Charts\Features\Frappe\Chart as ChartFeatures;

class Chart extends BaseChart
{
    use ChartFeatures;

    /**
     * Frappe dataset class.
     *
     * @var object
     */
    public $dataset = Dataset::class;

    /**
     * Stores the default colors.
     *
     * @var array
     */
    public $default_colors = [
        '#E3342F', '#F6993F', '#FFED4A', '#38C172', '#4DC0B5', '#3490DC', '#6574CD', '#9561E2', '#F66D9B',
    ];

    /**
     * Determines the special charts.
     *
     * @var array
     */
    public $special_datasets = [
        'pie', 'percentage',
    ];

    /**
     * Initiates the Frappe Chart.
     *
     * @return self
     */
    public function __construct()
    {
        parent::__construct();

        $this->container = 'charts::frappe.container';
        $this->script = 'charts::frappe.script';

        return $this->options([
            'barOptions' => [
                'spaceRatio' => 0.75,
            ],
        ]);
    }

    /**
     * Format the datasets.
     *
     * @param bool $strict
     * @param bool $noBraces
     *
     * @return self
     */
    public function formatOptions(bool $strict = false, bool $noBraces = false)
    {
        $colors = [];
        $default = 0;
        foreach ($this->datasets as $dataset) {
            $color = $this->default_colors[$default];
            if (array_key_exists('color', $dataset->options)) {
                $color = $dataset->options['color'];
                unset($dataset->options['color']);
            } else {
                $default++;
            }
            array_push($colors, $color);
        }

        $this->options([
            'colors' => $colors,
        ]);

        return parent::formatOptions($strict, $noBraces);
    }
}
