<?php

namespace App\Abstracts;

use App\Traits\Charts;
use App\Utilities\Date;

abstract class Widget
{
    use Charts;

    public $model;

    public $default_name = '';

    public $default_settings = [
        'width' => 'col-md-4',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function getDefaultName()
    {
        return trans($this->default_name);
    }

    public function getDefaultSettings()
    {
        return $this->default_settings;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function view($name, $data = [])
    {
        return view($name, array_merge(['class' => $this], (array) $data));
    }

    public function applyFilters($model, $args = ['date_field' => 'paid_at'])
    {
        if (empty(request()->get('start_date', null))) {
            return $model;
        }

        $start_date = request()->get('start_date') . ' 00:00:00';
        $end_date = request()->get('end_date') . ' 23:59:59';

        return $model->whereBetween($args['date_field'], [$start_date, $end_date]);
    }

    public function calculateDocumentTotals($model)
    {
        $open = $overdue = 0;

        $today = Date::today()->toDateString();

        if ($model->status == 'paid') {
            return [$open, $overdue];
        }

        $payments = 0;

        if ($model->status == 'partial') {
            foreach ($model->transactions as $transaction) {
                $payments += $transaction->getAmountConvertedToDefault();
            }
        }

        // Check if the invoice/bill is open or overdue
        if ($model->due_at > $today) {
            $open += $model->getAmountConvertedToDefault() - $payments;
        } else {
            $overdue += $model->getAmountConvertedToDefault() - $payments;
        }

        return [$open, $overdue];
    }
}
