<?php

namespace ConsoleTVs\Charts\Classes;

use Balping\JsonRaw\Encoder;
use Balping\JsonRaw\Raw;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class BaseChart
{
    /**
     * Stores the chart ID.
     *
     * @var string
     */
    public $id;

    /**
     * Stores the chart datasets.
     *
     * @var array
     */
    public $datasets = [];

    /**
     * Stores the dataset class to be used.
     *
     * @var object
     */
    protected $dataset = DatasetClass::class;

    /**
     * Stores the chart labels.
     *
     * @var array
     */
    public $labels = [];

    /**
     * Stores the chart container.
     *
     * @var string
     */
    public $container = '';

    /**
     * Stores the chart options.
     *
     * @var array
     */
    public $options = [];

    /**
     * Stores the plugins options.
     *
     * @var array
     */
    public $plugins = [];

    /**
     * Stores the plugin views.
     *
     * @var array
     */
    public $pluginsViews = [];

    /**
     * Stores the chart script.
     *
     * @var string
     */
    public $script = '';

    /**
     * Stores the chart type.
     *
     * @var string
     */
    public $type = '';

    /**
     * Stores the API url if the chart is loaded over API.
     *
     * @var string
     */
    public $api_url = '';

    /**
     * Determines if the loader is show.
     *
     * @var bool
     */
    public $loader = true;

    /**
     * Determines if the loader color.
     *
     * @var string
     */
    public $loaderColor = '#22292F';

    /**
     * Stores the height of the chart.
     *
     * @var int
     */
    public $height = 400;

    /**
     * Stores the width of the chart.
     *
     * @var int
     */
    public $width = null;

    /**
     * Stores the available chart letters to create the ID.
     *
     * @var array
     */
    public $scriptAttributes = [
        'type' => 'text/javascript',
    ];

    /**
     * Stores the available chart letters to create the ID.
     *
     * @var string
     */
    private $chartLetters = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * Chart constructor.
     */
    public function __construct()
    {
        $this->id = substr(str_shuffle(str_repeat($x = $this->chartLetters, ceil(25 / strlen($x)))), 1, 25);
    }

    /**
     * Adds a new dataset to the chart.
     *
     * @param string           $name
     * @param array|Collection $data
     */
    public function dataset(string $name, string $type, $data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $dataset = new $this->dataset($name, $type, $data);

        array_push($this->datasets, $dataset);

        return $dataset;
    }

    /**
     * Set the chart labels.
     *
     * @param array|Collection $labels
     *
     * @return self
     */
    public function labels($labels)
    {
        if ($labels instanceof Collection) {
            $labels = $labels->toArray();
        }

        $this->labels = $labels;

        return $this;
    }

    /**
     * Set the chart options.
     *
     * @param array|Collection $options
     * @param bool             $overwrite
     *
     * @return self
     */
    public function options($options, bool $overwrite = false)
    {
        if (!empty($options['plugins'])) {
            $options['plugins'] = new Raw(trim(preg_replace('/\s\s+/', ' ', $options['plugins'])));
        }

        if ($options instanceof Collection) {
            $options = $options->toArray();
        }
        if ($overwrite) {
            $this->options = $options;
        } else {
            $this->options = array_replace_recursive($this->options, $options);
        }

        return $this;
    }

    /**
     * Set the plugins options.
     *
     * @param array|Collection $options
     * @param bool             $overwrite
     *
     * @return self
     */
    public function plugins($plugins, bool $overwrite = true)
    {
        if ($plugins instanceof Collection) {
            $plugins = $plugins->toArray();
        }
        if ($overwrite) {
            $this->plugins = $plugins;
        } else {
            $this->plugins = array_replace_recursive($this->plugins, $plugins);
        }

        return $this;
    }

    /**
     * Set the chart container.
     *
     * @param string $container
     *
     * @return self
     */
    public function container(string $container = null)
    {
        if (!$container) {
            return View::make($this->container, ['chart' => $this]);
        }

        $this->container = $container;

        return $this;
    }

    /**
     * Set the chart script.
     *
     * @param string $script
     *
     * @return self
     */
    public function script(string $script = null)
    {
        if (count($this->datasets) == 0 && !$this->api_url) {
            throw new \Exception('No datasets provided, please provide at least one dataset to generate a chart');
        }

        if (!$script) {
            return View::make($this->script, ['chart' => $this]);
        }

        $this->script = $script;

        return $this;
    }

    /**
     * Set the chart type.
     *
     * @param string $type
     *
     * @return self
     */
    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the chart height.
     *
     * @param int $height
     *
     * @return self
     */
    public function height(int $height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set the chart width.
     *
     * @param int $width
     *
     * @return self
     */
    public function width(int $width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Formats the type to be a correct output.
     *
     * @return string
     */
    public function formatType()
    {
        return $this->type ? $this->type : $this->datasets[0]->type;
    }

    /**
     * Formats the labels to be a correct output.
     *
     * @return string
     */
    public function formatLabels()
    {
        return Encoder::encode($this->labels);
    }

    /**
     * Formats the chart options.
     *
     * @param bool $strict
     *
     * @return string
     */
    public function formatOptions(bool $strict = false, bool $noBraces = false)
    {
        if (!$strict && count($this->options) === 0) {
            return '';
        }

        $options = Encoder::encode($this->options);

        return $noBraces ? substr($options, 1, -1) : $options;
    }

    /**
     * Formats the plugins options.
     *
     * @param bool $strict
     *
     * @return string
     */
    public function formatPlugins(bool $strict = false, bool $noBraces = false)
    {
        if (!$strict && count($this->plugins) === 0) {
            return '';
        }

        $plugins = str_replace('"', '', Encoder::encode($this->plugins));

        return $noBraces ? substr($plugins, 1, -1) : $plugins;
    }

    /**
     * Use this to pass values to json without any modification
     * Useful for defining callbacks.
     *
     * @param string $value
     *
     * @return \Balping\JsonRaw\Raw
     */
    public function rawObject(string $value)
    {
        return new Raw($value);
    }

    /**
     * Reset the chart options.
     *
     * @return self
     */
    public function reset()
    {
        return $this->options([], true);
    }

    /**
     * Formats the datasets for the output.
     *
     * @return string
     */
    public function formatDatasets()
    {
        // This little boy was commented because it's not compatible
        // in laravel < 5.4
        //
        // return Collection::make($this->datasets)
        //     ->each
        //     ->matchValues(count($this->labels))
        //     ->map
        //     ->format($this->labels)
        //     ->toJson();

        return Encoder::encode(
            Collection::make($this->datasets)
                ->each(function ($dataset) {
                    $dataset->matchValues(count($this->labels));
                })
                ->map(function ($dataset) {
                    return $dataset->format($this->labels);
                })
                ->toArray()
        );
    }

    /**
     * Indicates that the chart information will be loaded over API.
     *
     * @param string $api_url
     *
     * @return self
     */
    public function load(string $api_url)
    {
        $this->api_url = $api_url;

        return $this;
    }

    /**
     * Determines if the chart should show a loader.
     *
     * @param bool $loader
     *
     * @return self
     */
    public function loader(bool $loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Determines the loader color.
     *
     * @param string $color
     *
     * @return self
     */
    public function loaderColor(string $color)
    {
        $this->loaderColor = $color;

        return $this;
    }

    /**
     * Alias for the formatDatasets() method.
     *
     * @return string
     */
    public function api()
    {
        return $this->formatDatasets();
    }

    /**
     * Sets an HTML attribute the the script tag of the chart.
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function setScriptAttribute(string $key, string $value)
    {
        $this->scriptAttributes[$key] = $value;

        return $this;
    }

    /**
     * Returns the string formatting of the script attributes.
     *
     * @return string
     */
    public function displayScriptAttributes(): string
    {
        $result = '';
        foreach ($this->scriptAttributes as $key => $value) {
            echo " {$key}=\"{$value}\"";
        }

        return $result;
    }

    /**
     * Formats the container options.
     *
     * @param string $type
     * @param bool   $maxIfNull
     *
     * @return string
     */
    public function formatContainerOptions(string $type = 'css', bool $maxIfNull = false)
    {
        $options = '';
        $height = ($maxIfNull && !$this->height) ? '100%' : $this->height;
        $width = ($maxIfNull && !$this->width) ? '100%' : $this->width;

        switch ($type) {
            case 'css':
                $options .= " style='";
                (!$height) ?: $options .= "height: {$height}px !important;";
                (!$width) ?: $options .= "width: {$width}px !important;";
                $options .= "' ";
                break;
            case 'js':
                if ($height) {
                    if (is_int($height)) {
                        $options .= "height: {$height}, ";
                    } else {
                        $options .= "height: '{$height}', ";
                    }
                }
                if ($width) {
                    if (is_int($width)) {
                        $options .= "width: {$width}, ";
                    } else {
                        $options .= "width: '{$width}', ";
                    }
                }
                break;
            default:
                (!$height) ?: $options .= " height='{$this->height}' ";
                (!$this->width) ?: $options .= " width='{$this->width}' ";
        }

        return $options;
    }
}
