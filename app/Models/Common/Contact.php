<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\Media;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use Cloneable, Contacts, Currencies, Media, Notifiable;

    protected $table = 'contacts';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'type', 'name', 'email', 'user_id', 'tax_number', 'phone', 'address', 'website', 'currency_code', 'reference', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'enabled'];

    public function bills()
    {
        return $this->hasMany('App\Models\Purchase\Bill');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function expense_transactions()
    {
        return $this->transactions()->where('type', 'expense');
    }

    public function income_transactions()
    {
        return $this->transactions()->where('type', 'income');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Sale\Invoice');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    /**
     * Scope to only include contacts of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->table . '.type', (array) $types);
    }

    /**
     * Scope to include only vendors.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVendor($query)
    {
        return $query->whereIn($this->table . '.type', (array) $this->getVendorTypes());
    }

    /**
     * Scope to include only customers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomer($query)
    {
        return $query->whereIn($this->table . '.type', (array) $this->getCustomerTypes());
    }

    public function scopeEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }

    public function onCloning($src, $child = null)
    {
        $this->email = null;
        $this->user_id = null;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getLogoAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('logo')) {
            return $value;
        } elseif (!$this->hasMedia('logo')) {
            return false;
        }

        return $this->getMedia('logo')->last();
    }

    public function getUnpaidAttribute()
    {
        $amount = 0;

        $collection = in_array($this->type, $this->getCustomerTypes()) ? 'invoices' : 'bills';

        $this->$collection()->accrued()->notPaid()->each(function ($item) use (&$amount) {
            $unpaid = $item->amount - $item->paid;

            $amount += $this->convertToDefault($unpaid, $item->currency_code, $item->currency_rate);
        });

        return $amount;
    }
}
