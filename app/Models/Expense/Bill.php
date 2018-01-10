<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;
use App\Traits\DateTime;
use Bkwld\Cloner\Cloneable;
use Sofa\Eloquence\Eloquence;
use Plank\Mediable\Mediable;

class Bill extends Model
{
    use Cloneable, Currencies, DateTime, Eloquence, Mediable;

    protected $table = 'bills';

    protected $dates = ['deleted_at', 'billed_at', 'due_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_number', 'order_number', 'bill_status_code', 'billed_at', 'due_at', 'amount', 'currency_code', 'currency_rate', 'vendor_id', 'vendor_name', 'vendor_email', 'vendor_tax_number', 'vendor_phone', 'vendor_address', 'notes'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['bill_number', 'vendor_name', 'amount', 'status.name', 'billed_at', 'due_at'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'bill_number'    => 10,
        'order_number'   => 10,
        'vendor_name'    => 10,
        'vendor_email'   => 5,
        'vendor_phone'   => 2,
        'vendor_address' => 1,
        'notes'          => 2,
    ];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    protected $cloneable_relations = ['items', 'totals'];

    public function vendor()
    {
        return $this->belongsTo('App\Models\Expense\Vendor');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Expense\BillStatus', 'bill_status_code', 'code');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Expense\BillItem');
    }

    public function totals()
    {
        return $this->hasMany('App\Models\Expense\BillTotal');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Expense\BillPayment');
    }

    public function histories()
    {
        return $this->hasMany('App\Models\Expense\BillHistory');
    }

    public function scopeDue($query, $date)
    {
        return $query->where('due_at', '=', $date);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function scopeAccrued($query)
    {
        return $query->where('bill_status_code', '!=', 'new');
    }

    public function onCloning($src, $child = null)
    {
        $this->bill_status_code = 'draft';
    }

    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    /**
     * Convert currency rate to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getAttachmentAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->last();
    }
}
