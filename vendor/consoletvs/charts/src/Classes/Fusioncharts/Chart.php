<?php

namespace ConsoleTVs\Charts\Classes\Fusioncharts;

use ConsoleTVs\Charts\Classes\BaseChart;
use ConsoleTVs\Charts\Features\Fusioncharts\Chart as ChartFeatures;
use Illuminate\Support\Collection;

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
     * Theese array stores the types that are maintained to fusioncharts.
     *
     * @var array
     */
    public $keepType = ['pie2d', 'doughnut2d'];

    /**
     * Determines the combo type of chart.
     *
     * @var array
     */
    public $comboType = 'mscombi2d';

    /**
     * Initiates the Chartjs Line Chart.
     *
     * @return self
     */
    public function __construct()
    {
        parent::__construct();

        $this->container = 'charts::fusioncharts.container';
        $this->script = 'charts::fusioncharts.script';

        return $this->options([
            'bgColor'                 => '#ffffff',
            'borderAlpha'             => 0,
            'canvasBorderAlpha'       => 0,
            'usePlotGradientColor'    => false,
            'plotBorderAlpha'         => 0,
            'divlineColor'            => '#22292F',
            'divLineIsDashed'         => true,
            'showAlternateHGridColor' => false,
            'captionFontBold'         => true,
            'captionFontSize'         => 14,
            'subcaptionFontBold'      => false,
            'subcaptionFontSize'      => 14,
            'legendBorderAlpha'       => 0,
            'legendShadow'            => 0,
            'hoverfillcolor'          => '#CCCCCC',
            'piebordercolor'          => '#FFFFFF',
            'hoverfillcolor'          => '#CCCCCC',
            'use3DLighting'           => false,
            'showShadow'              => false,
        ]);
    }

    /**
     * Formats the labels for fusioncharts.
     *
     * @return string
     */
    public function formatLabels()
    {
        return Collection::make($this->labels)
            ->map(function ($label) {
                return ['label' => $label];
            })
            ->toJson();
    }
}
