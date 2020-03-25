<?php

namespace App\Models\Sale;

use App\Abstracts\DocumentModel;
use App\Traits\Sales;

class Invoice extends DocumentModel
{
    use Sales;

    protected $table = 'invoices';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['attachment', 'amount_without_tax', 'discount', 'paid', 'status_label'];

    protected $dates = ['deleted_at', 'invoiced_at', 'due_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_number', 'order_number', 'status', 'invoiced_at', 'due_at', 'amount', 'currency_code', 'currency_rate', 'contact_id', 'contact_name', 'contact_email', 'contact_tax_number', 'contact_phone', 'contact_address', 'notes', 'category_id', 'parent_id', 'footer'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['invoice_number', 'contact_name', 'amount', 'status' , 'invoiced_at', 'due_at'];

    protected $reconciled_amount = [];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['items', 'recurring', 'totals'];

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na')]);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Sale\InvoiceItem');
    }

    public function item_taxes()
    {
        return $this->hasMany('App\Models\Sale\InvoiceItemTax');
    }

    public function histories()
    {
        return $this->hasMany('App\Models\Sale\InvoiceHistory');
    }

    public function payments()
    {
        return $this->transactions();
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function totals()
    {
        return $this->hasMany('App\Models\Sale\InvoiceTotal');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'document_id')->where('type', 'income');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('invoiced_at', 'desc');
    }

    public function scopeNumber($query, $number)
    {
        return $query->where('invoice_number', '=', $number);
    }

    public function onCloning($src, $child = null)
    {
        $this->status = 'draft';
        $this->invoice_number = $this->getNextInvoiceNumber();
    }

    public function getSentAtAttribute($value)
    {
        $sent = $this->histories()->where('status', 'sent')->first();

        return ($sent) ? $sent->created_at : null;
    }
}
