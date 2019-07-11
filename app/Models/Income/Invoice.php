<?php

namespace App\Models\Income;

use App\Models\Model;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Incomes;
use App\Traits\Media;
use App\Traits\Recurring;
use Bkwld\Cloner\Cloneable;
use Sofa\Eloquence\Eloquence;
use Date;

class Invoice extends Model
{
    use Cloneable, Currencies, DateTime, Eloquence, Incomes, Media, Recurring;

    protected $table = 'invoices';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['attachment', 'amount_without_tax', 'discount', 'paid'];

    protected $dates = ['deleted_at', 'invoiced_at', 'due_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_number', 'order_number', 'invoice_status_code', 'invoiced_at', 'due_at', 'amount', 'currency_code', 'currency_rate', 'customer_id', 'customer_name', 'customer_email', 'customer_tax_number', 'customer_phone', 'customer_address', 'notes', 'category_id', 'parent_id'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['invoice_number', 'customer_name', 'amount', 'status' , 'invoiced_at', 'due_at', 'invoice_status_code'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'invoice_number'   => 10,
        'order_number'     => 10,
        'customer_name'    => 10,
        'customer_email'   => 5,
        'customer_phone'   => 2,
        'customer_address' => 1,
        'notes'            => 2,
    ];

    protected $reconciled_amount = [];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['items', 'recurring', 'totals'];

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Income\Customer');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Income\InvoiceItem');
    }

    public function item_taxes()
    {
        return $this->hasMany('App\Models\Income\InvoiceItemTax');
    }

    public function histories()
    {
        return $this->hasMany('App\Models\Income\InvoiceHistory');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Income\InvoicePayment');
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Income\InvoiceStatus', 'invoice_status_code', 'code');
    }

    public function totals()
    {
        return $this->hasMany('App\Models\Income\InvoiceTotal');
    }

    public function scopeDue($query, $date)
    {
        return $query->whereDate('due_at', '=', $date);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function scopeAccrued($query)
    {
        return $query->where('invoice_status_code', '<>', 'draft');
    }

    public function scopePaid($query)
    {
        return $query->where('invoice_status_code', '=', 'paid');
    }

    public function scopeNotPaid($query)
    {
        return $query->where('invoice_status_code', '<>', 'paid');
    }

    public function onCloning($src, $child = null)
    {
        $this->invoice_status_code = 'draft';
        $this->invoice_number = $this->getNextInvoiceNumber();
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

    /**
     * Get the discount percentage.
     *
     * @return string
     */
    public function getDiscountAttribute()
    {
        $percent = 0;

        $discount = $this->totals()->where('code', 'discount')->value('amount');

        if ($discount) {
            $sub_total = $this->totals()->where('code', 'sub_total')->value('amount');

            $percent = number_format((($discount * 100) / $sub_total), 0);
        }

        return $percent;
    }

    /**
     * Get the amount without tax.
     *
     * @return string
     */
    public function getAmountWithoutTaxAttribute()
    {
        $amount = $this->amount;

        $this->totals()->where('code', 'tax')->each(function ($tax) use(&$amount) {
            $amount -= $tax->amount;
        });

        return $amount;
    }

    /**
     * Get the paid amount.
     *
     * @return string
     */
    public function getPaidAttribute()
    {
        if (empty($this->amount)) {
            return false;
        }

        $paid = 0;
        $reconciled = $reconciled_amount = 0;

        if ($this->payments->count()) {
            $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

            foreach ($this->payments as $item) {
                if ($this->currency_code == $item->currency_code) {
                    $amount = (double) $item->amount;
                } else {
                    $default_model = new InvoicePayment();
                    $default_model->default_currency_code = $this->currency_code;
                    $default_model->amount = $item->amount;
                    $default_model->currency_code = $item->currency_code;
                    $default_model->currency_rate = $currencies[$item->currency_code];

                    $default_amount = (double) $default_model->getDivideConvertedAmount();

                    $convert_model = new InvoicePayment();
                    $convert_model->default_currency_code = $item->currency_code;
                    $convert_model->amount = $default_amount;
                    $convert_model->currency_code = $this->currency_code;
                    $convert_model->currency_rate = $currencies[$this->currency_code];

                    $amount = (double) $convert_model->getDynamicConvertedAmount();
                }

                $paid += $amount;

                if ($item->reconciled) {
                    $reconciled_amount = +$amount;
                }
            }
        }

        if ($this->amount == $reconciled_amount) {
            $reconciled = 1;
        }

        $this->setAttribute('reconciled', $reconciled);

        return $paid;
    }
}
