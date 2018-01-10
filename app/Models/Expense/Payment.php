<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;
use App\Traits\DateTime;
use Bkwld\Cloner\Cloneable;
use Sofa\Eloquence\Eloquence;
use Plank\Mediable\Mediable;

class Payment extends Model
{
    use Cloneable, Currencies, DateTime, Eloquence, Mediable;

    protected $table = 'payments';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'account_id', 'paid_at', 'amount', 'currency_code', 'currency_rate', 'vendor_id', 'description', 'category_id', 'payment_method', 'reference'];

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

    public static function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
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
