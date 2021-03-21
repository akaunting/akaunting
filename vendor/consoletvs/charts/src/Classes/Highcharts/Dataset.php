<?php

namespace ConsoleTVs\Charts\Classes\Highcharts;

use ConsoleTVs\Charts\Classes\DatasetClass;
use ConsoleTVs\Charts\Features\Highcharts\Dataset as DatasetFeatures;
use Illuminate\Support\Collection;

class Dataset extends DatasetClass
{
    use DatasetFeatures;

    /**
     * Store the private datasets that contains a special formating.
     *
     * @var array
     */
    public $specialDatasets = ['pie'];

    /**
     * Creates a new dataset with the given values.
     *
     * @param string $name
     * @param string $type
     * @param array  $values
     */
    public function __construct(string $name, string $type, array $values)
    {
        parent::__construct($name, $type, $values);
    }

    /**
     * Dataset representation.
     *
     * @var array
     */
    public function format(array $labels = [])
    {
        return array_merge($this->options, [
            'data' => $this->formatValues($labels),
            'name' => $this->name,
            'type' => strtolower($this->type),
        ]);
    }

    /**
     * Formats the values.
     *
     * @param array $labels
     *
     * @return array
     */
    protected function formatValues(array $labels)
    {
        if (in_array(strtolower($this->type), $this->specialDatasets)) {
            $colors = $this->getColors($labels);

            return Collection::make($this->values)
                ->map(function ($value, $key) use ($colors, $labels) {
                    $val = [
                        'name' => $labels[$key],
                        'y'    => $value,
                    ];

                    if ($colors->count() > 0) {
                        $val['color'] = $colors->get($key);
                    }

                    return $val;
                })->toArray();
        }

        return $this->values;
    }

    /**
     * Get the dataset colors;.
     *
     * @param array $labels
     *
     * @return Collection
     */
    protected function getColors(array $labels)
    {
        $colors = Collection::make(array_key_exists('color', $this->options) ? $this->options['color'] : []);

        while ($colors->count() < count($labels)) {
            $colors->push($this->undefinedColor);
        }

        return $colors;
    }
}
