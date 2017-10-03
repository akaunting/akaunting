<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;
use App\Traits\DateTime;
use Sofa\Eloquence\Eloquence;

class Payment extends Model
{
    use Currencies, DateTime, Eloquence;

    protected $table = 'payments';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'account_id', 'paid_at', 'amount', 'currency_code', 'currency_rate', 'vendor_id', 'description', 'category_id', 'payment_method', 'reference', 'attachment'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['paid_at', 'amount', 'category.name', 'account.name'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'accounts.name',
        'categories.name',
        'vendors.name' ,
        'description'  ,
    ];

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Expense\Vendor');
    }

    public function transfers()
    {
        return $this->hasMany('App\Models\Banking\Transfer');
    }

    /**
     * Convert amount to float.
     *
     * @param  string  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (float) $value;
    }

    /**
     * Convert currency rate to float.
     *
     * @param  string  $value
     * @return void
     */
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (float) $value;
    }

    public static function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }
}
