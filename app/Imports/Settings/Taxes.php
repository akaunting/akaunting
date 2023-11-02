<?php

namespace App\Imports\Settings;

use App\Abstracts\Import;
use App\Http\Requests\Setting\Tax as Request;
use App\Models\Setting\Tax as Model;

class Taxes extends Import
{
    public $request_class = Request::class;

    public function model(array $row)
    {
        if (empty($row)) {
            return;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);
        
        if ($row['type'] == 'compound') {
            $compound_tax = Model::where('type', 'compound')->first();

            if ($compound_tax) {
                $this->request_class = null;

                // TODO: Add exception error
                // throw new \Exception('Compound tax already exists.');

                return [];
            }
        }

        return $row;
    }

}
