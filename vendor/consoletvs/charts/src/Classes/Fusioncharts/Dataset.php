<?php

namespace ConsoleTVs\Charts\Classes\Fusioncharts;

use ConsoleTVs\Charts\Classes\DatasetClass;
use ConsoleTVs\Charts\Features\Fusioncharts\Dataset as DatasetFeatures;
use Illuminate\Support\Collection;

class Dataset extends DatasetClass
{
    use DatasetFeatures;

    /**
     * Store the private datasets that contains a special formatting.
     *
     * @var array
     */
    public $specialDatasets = ['pie2d', 'doughnut2d'];

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
            'data'       => $this->formatValues($labels),
            'seriesName' => $this->name,
            'renderAs'   => strtolower($this->type),
        ]);
    }

    /**
     * Formats the chart values.
     *
     * @param array $labels
     *
     * @return array
     */
    protected function formatValues(array $labels)
    {
        $values = Collection::make($this->values);

        if (in_array(strtolower($this->type), $this->specialDatasets)) {
            $colors = $this->getColors($labels);

            return $values->map(function ($value, $key) use ($colors, $labels) {
                $val = [
                    'label' => $labels[$key],
                    'value' => $value,
                ];

                if ($colors->count() > 0) {
                    $val['color'] = $colors->get($key);
                }

                return $val;
            })->toArray();
        }

        return $values->map(function ($value) {
            return [
                'value' => $value,
            ];
        })->toArray();
    }

    /**
     * Get the chart colors.
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
