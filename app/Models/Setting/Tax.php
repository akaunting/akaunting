<?php

namespace App\Models\Setting;

use App\Models\Model;

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

    public function bill_items()
    {
        return $this->hasMany('App\Models\Expense\BillItem');
    }

    public function invoice_items()
    {
        return $this->hasMany('App\Models\Income\InvoiceItem');
    }

    public function canDelete()
    {
        $error = false;

        if ($items = $this->items()->count()) {
            $error['items'] = $items;
        }

        if ($bills = $this->bill_items()->count()) {
            $error['bills'] = $bills;
        }

        if ($invoices = $this->invoice_items()->count()) {
            $error['invoices'] = $invoices;
        }

        if ($error) {
            return $error;
        }

        return true;
    }
}
