<?php

return [

    'options' => [
        'chart' => [
            'width' => '100%',
            'height' => 300,
            'toolbar' => [
                'show' => true,
            ],
            'stacked' => false,
            'zoom' => [
                'enabled' => false,
            ],
            'fontFamily' => '"Quicksand", sans-serif',
            'foreColor' => '#595959',
        ],

        'legend' => [
            'horizontalAlign' => 'left',
        ],

        'dataLabels' => [
            'enabled' => false,
        ],

        'grid' => [
            'show' => true,
            'borderColor' => '#E5E7EB',
        ],

        'markers' => [
            'size' => 5,
            /*'colors' => [
                '#ffffff',
            ],
            'strokeColors' => '#444444',
            'strokeWidth' => 2,*/
            'hover' => [
                'size' => 8,
            ],
        ],

        'states' => [
            'hover' => [
                'filter' => [
                    'value' => 0.01,
                ],
            ],
        ],

        'stroke' => [
            'show' => true,
            'curve' => 'straight',
            'width' => 2,
        ],

        'fill' => [
            'opacity' => 1,
        ],

        'tooltip' => [
            'shared' => false,
            'followCursor' => true,
            'onDatasetHover' => [
                'highlightDataSeries' => true,
            ],
        ],

        'xaxis' => [
            'labels' => [
                'rotate' => 0,
            ],
            'tickAmount' => 3,
        ],

    ],

];
