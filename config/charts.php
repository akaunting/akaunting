<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default settings for charts.
    |--------------------------------------------------------------------------
    */

    'default' => [
        'type' => 'line', // The default chart type.
        'library' => 'material', // The default chart library.
        'element_label' => '', // The default chart element label.
        'empty_dataset_label' => 'No Data Set',
        'empty_dataset_value' => 0,
        'title' => '', // Default chart title.
        'height' => 400, // 0 Means it will take 100% of the division height.
        'width' => 0, // 0 Means it will take 100% of the division width.
        'responsive' => false, // Not recommended since all libraries have diferent sizes.
        'background_color' => 'inherit', // The chart division background color.
        'colors' => [], // Default chart colors if using no template is set.
        'one_color' => false, // Only use the first color in all values.
        'template' => 'material', // The default chart color template.
        'legend' => true, // Whether to enable the chart legend (where applicable).
        'x_axis_title' => false, // The title of the x-axis
        'y_axis_title' => null, // The title of the y-axis (When set to null will use element_label value).
        'loader' => [
            'active' => false, // Determines the if loader is active by default.
            'duration' => 500, // In milliseconds.
            'color' => '#6da252', // Determines the default loader color.
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | All the color templates available for the charts.
    |--------------------------------------------------------------------------
    */
    'templates' => [
        'material' => [
            '#2196F3', '#F44336', '#FFC107',
        ],
        'red-material' => [
            '#B71C1C', '#F44336', '#E57373',
        ],
        'indigo-material' => [
            '#1A237E', '#3F51B5', '#7986CB',
        ],
        'blue-material' => [
            '#0D47A1', '#2196F3', '#64B5F6',
        ],
        'teal-material' => [
            '#004D40', '#009688', '#4DB6AC',
        ],
        'green-material' => [
            '#1B5E20', '#4CAF50', '#81C784',
        ],
        'yellow-material' => [
            '#F57F17', '#FFEB3B', '#FFF176',
        ],
        'orange-material' => [
            '#E65100', '#FF9800', '#FFB74D',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets required by the libraries.
    |--------------------------------------------------------------------------
    */

    'assets' => [
        'global' => [
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js',
            ],
        ],

        'canvas-gauges' => [
            'scripts' => [
                //'https://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.2/all/gauge.min.js',
            ],
        ],

        'chartist' => [
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js',
            ],
            'styles' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css',
            ],
        ],

        'chartjs' => [
            'scripts' => [
                app()->runningInConsole() ? '' : asset('public/js/chartjs/Chart.min.js'),
            ],
        ],

        'fusioncharts' => [
            'scripts' => [
                //'https://static.fusioncharts.com/code/latest/fusioncharts.js',
                //'https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js',
            ],
        ],

        'google' => [
            'scripts' => [
                //'https://www.google.com/jsapi',
                //'https://www.gstatic.com/charts/loader.js',
                //"google.charts.load('current', {'packages':['corechart', 'gauge', 'geochart', 'bar', 'line']})",
            ],
        ],

        'highcharts' => [
            'styles' => [
                // The following CSS is not added due to color compatibility errors.
                // 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/css/highcharts.css',
            ],
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/js/modules/offline-exporting.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/map.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/data.js',
                //'https://code.highcharts.com/mapdata/custom/world.js',
            ],
        ],

        'justgage' => [
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.2/justgage.min.js',
            ],
        ],

        'morris' => [
            'styles' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
            ],
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
            ],
        ],

        'plottablejs' => [
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.8.0/plottable.min.js',
            ],
            'styles' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.2.0/plottable.css',
            ],
        ],

        'progressbarjs' => [
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js',
            ],
        ],

        'c3' => [
            'scripts' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
                //'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js',
            ],
            'styles' => [
                //'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css',
            ],
        ],
    ],
];
