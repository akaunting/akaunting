<?php

namespace ConsoleTVs\Charts\Classes;

use Illuminate\Support\Collection;

class DatasetClass
{
    /**
     * Defines the name of the dataset.
     *
     * @var string
     */
    public $name = 'Undefined';

    /**
     * Defines the dataset type.
     *
     * @var string
     */
    public $type = '';

    /**
     * Stores the dataset values.
     *
     * @var array
     */
    public $values = [];

    /**
     * Stores the dataset options.
     *
     * @var array
     */
    public $options = [];

    /**
     * Defines the undefined color.
     *
     * @var string
     */
    public $undefinedColor = '#22292F';

    /**
     * Creates a new dataset with the given values.
     *
     * @param string $name
     * @param string $type
     * @param array  $values
     */
    public function __construct(string $name, string $type, array $values)
    {
        $this->name = $name;
        $this->type = $type;
        $this->values = $values;

        return $this;
    }

    /**
     * Set the dataset type.
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
     * Set the dataset values.
     *
     * @param array|Collection $values
     *
     * @return self
     */
    public function values($values)
    {
        if ($values instanceof Collection) {
            $values = $values->toArray();
        }

        $this->values = $values;

        return $this;
    }

    /**
     * Set the dataset options.
     *
     * @param array|Collection $options
     * @param bool             $overwrite
     *
     * @return self
     */
    public function options($options, bool $overwrite = false)
    {
        if ($overwrite) {
            $this->options = $options;
        } else {
            $this->options = array_replace_recursive($this->options, $options);
        }

        return $this;
    }

    /**
     * Matches the values of the dataset with the given number.
     *
     * @param int  $values
     * @param bool $strict
     *
     * @return void
     */
    public function matchValues(int $values, bool $strict = false)
    {
        while (count($this->values) < $values) {
            array_push($this->values, 0);
        }

        if ($strict) {
            $this->values = array_slice($this->values, 0, $values);
        }
    }
}
