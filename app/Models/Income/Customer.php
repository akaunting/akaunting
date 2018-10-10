<?php

namespace App\Models\Income;

use App\Models\Model;
use Bkwld\Cloner\Cloneable;
use App\Traits\Currencies;
use Illuminate\Notifications\Notifiable;
use Sofa\Eloquence\Eloquence;

class Customer extends Model
{
    use Cloneable, Currencies, Eloquence, Notifiable;

    protected $table = 'customers';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'name', 'email', 'tax_number', 'phone', 'address', 'website', 'currency_code', 'reference', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'enabled'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'    => 10,
        'email'   => 5,
        'phone'   => 2,
        'website' => 2,
        'address' => 1,
    ];

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice');
    }

    public function revenues()
    {
        return $this->hasMany('App\Models\Income\Revenue');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    public function onCloning($src, $child = null)
    {
        $this->user_id = null;
    }

    public function getUnpaidAttribute()
    {
        $amount = 0;

        $invoices = $this->invoices()->accrued()->notPaid()->get();

        foreach ($invoices as $invoice) {
            $invoice_amount = $invoice->amount - $invoice->paid;

            $amount += $this->dynamicConvert(setting('general.default_currency'), $invoice_amount, $invoice->currency_code, $invoice->currency_rate, false);
        }

        return $amount;
    }
}
