<?php

namespace ConsoleTVs\Charts\Classes\Chartjs;

use ConsoleTVs\Charts\Classes\DatasetClass;
use ConsoleTVs\Charts\Features\Chartjs\Dataset as DatasetFeatures;

class Dataset extends DatasetClass
{
    use DatasetFeatures;

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

        $this->options([
            'borderWidth' => 2,
        ]);
    }

    /**
     * Formats the dataset for chartjs.
     *
     * @return array
     */
    public function format()
    {
        return array_merge($this->options, [
            'data'  => $this->values,
            'label' => $this->name,
            'type'  => $this->type,
        ]);
    }
}
