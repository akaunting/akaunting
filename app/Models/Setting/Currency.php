<?php

namespace App\Models\Setting;

use App\Abstracts\Model;
use App\Models\Document\Document;
use App\Traits\Contacts;
use App\Traits\Transactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use Contacts, HasFactory, Transactions;

    protected $table = 'currencies';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'rate',
        'enabled',
        'precision',
        'symbol',
        'symbol_first',
        'decimal_mark',
        'thousands_separator',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'double',
        'enabled' => 'boolean',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'code', 'rate', 'enabled'];

    public function accounts()
    {
        return $this->hasMany('App\Models\Banking\Account', 'currency_code', 'code');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document', 'currency_code', 'code');
    }

    public function bills()
    {
        return $this->documents()->where('type', Document::BILL_TYPE);
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Common\Contact', 'currency_code', 'code');
    }

    public function customers()
    {
        return $this->contacts()->whereIn('type', (array) $this->getCustomerTypes());
    }

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getIncomeTypes());
    }

    public function invoices()
    {
        return $this->documents()->where('type', Document::INVOICE_TYPE);
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'currency_code', 'code');
    }

    public function vendors()
    {
        return $this->contacts()->whereIn('type', (array) $this->getVendorTypes());
    }

    /**
     * Get the current precision.
     *
     * @return string
     */
    public function getPrecisionAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.precision');
        }

        return (int) $value;
    }

    /**
     * Get the current symbol.
     *
     * @return string
     */
    public function getSymbolAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.symbol');
        }

        return $value;
    }

    /**
     * Get the current symbol_first.
     *
     * @return string
     */
    public function getSymbolFirstAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.symbol_first');
        }

        return $value;
    }

    /**
     * Get the current decimal_mark.
     *
     * @return string
     */
    public function getDecimalMarkAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.decimal_mark');
        }

        return $value;
    }

    /**
     * Get the current thousands_separator.
     *
     * @return string
     */
    public function getThousandsSeparatorAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.thousands_separator');
        }

        return $value;
    }

    /**
     * Scope currency by code.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCode($query, $code)
    {
        return $query->where($this->table . '.code', $code);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Currency::new();
    }
}
