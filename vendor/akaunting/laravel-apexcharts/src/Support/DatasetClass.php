<?php

namespace Akaunting\Apexcharts\Support;

use Illuminate\Support\Collection;

class DatasetClass
{
    public string $name = 'Undefined';

    public string $type = '';

    public array $data = [];

    public array $options = [];

    public string $undefinedColor = '#22292F';

    public function __construct(string $name, string $type, array $data)
    {
        $this->name = $name;
        $this->type = $type;
        $this->data = $data;

        return $this;
    }

    public function type(string $type): DatasetClass
    {
        $this->type = $type;

        return $this;
    }

    public function data(array|Collection $data): DatasetClass
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $this->data = $data;

        return $this;
    }

    public function options(array|Collection $options, bool $overwrite = false): DatasetClass
    {
        if ($overwrite) {
            $this->options = $options;
        } else {
            $this->options = array_replace_recursive($this->options, $options);
        }

        return $this;
    }

    /**
     * Matches the data of the dataset with the given number.
     */
    public function matchdata(int $data, bool $strict = false): void
    {
        while (count($this->data) < $data) {
            array_push($this->data, 0);
        }

        if ($strict) {
            $this->data = array_slice($this->data, 0, $data);
        }
    }
}
