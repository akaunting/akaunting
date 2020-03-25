<?php

namespace App\Models\Purchase;

use App\Abstracts\DocumentModel;
use App\Traits\Purchases;

class Bill extends DocumentModel
{
    use Purchases;

    protected $table = 'bills';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['attachment', 'amount_without_tax', 'discount', 'paid', 'status_label'];

    protected $dates = ['deleted_at', 'billed_at', 'due_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_number', 'order_number', 'status', 'billed_at', 'due_at', 'amount', 'currency_code', 'currency_rate', 'contact_id', 'contact_name', 'contact_email', 'contact_tax_number', 'contact_phone', 'contact_address', 'notes', 'category_id', 'parent_id'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['bill_number', 'contact_name', 'amount', 'status', 'billed_at', 'due_at'];

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

    public function histories()
    {
        return $this->hasMany('App\Models\Purchase\BillHistory');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Purchase\BillItem');
    }

    public function item_taxes()
    {
        return $this->hasMany('App\Models\Purchase\BillItemTax');
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function totals()
    {
        return $this->hasMany('App\Models\Purchase\BillTotal');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'document_id')->where('type', 'expense');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('billed_at', 'desc');
    }

    public function scopeNumber($query, $number)
    {
        return $query->where('bill_number', '=', $number);
    }

    public function onCloning($src, $child = null)
    {
        $this->status = 'draft';
        $this->bill_number = $this->getNextBillNumber();
    }

    public function getReceivedAtAttribute($value)
    {
        $received = $this->histories()->where('status', 'received')->first();

        return ($received) ? $received->created_at : null;
    }
}
