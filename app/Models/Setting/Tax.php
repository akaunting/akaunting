<?php

namespace App\Models\Setting;

use App\Models\Model;
use App\Models\Item\Item;
use App\Models\Expense\Bill;
use App\Models\Income\Invoice;

class Tax extends Model
{

    protected $table = 'taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'rate', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'rate', 'enabled'];

    public function items()
    {
        return $this->hasMany('App\Models\Item\Item');
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice');
    }

    public function canDelete()
    {
        $error = false;

        $items = $this->items();
        if ($items->count()) {
            $error['items'] = $items->count();
        }

        $bills = $this->bills();
        if ($bills->count()) {
            $error['bills'] = $bills->count();
        }

        $invoices = $this->invoices();
        if ($invoices->count()) {
            $error['invoices'] = $invoices->count();
        }

        if ($error) {
            return $error;
        }

        return true;
    }
}
