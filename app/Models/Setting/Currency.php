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
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate'          => 'double',
        'enabled'       => 'boolean',
        'deleted_at'    => 'datetime',
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
        return $this->documents()->where('documents.type', Document::BILL_TYPE);
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Common\Contact', 'currency_code', 'code');
    }

    public function customers()
    {
        return $this->contacts()->whereIn('contacts.type', (array) $this->getCustomerTypes());
    }

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getIncomeTypes());
    }

    public function invoices()
    {
        return $this->documents()->where('documents.type', Document::INVOICE_TYPE);
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'currency_code', 'code');
    }

    public function vendors()
    {
        return $this->contacts()->whereIn('contacts.type', (array) $this->getVendorTypes());
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
        return $query->where($this->qualifyColumn('code'), $code);
    }

    /**
     * Get the current precision.
     *
     * @return string
     */
    public function getPrecisionAttribute($value)
    {
        if (is_null($value)) {
            return config('money.currencies.' . $this->code . '.precision');
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
            return config('money.currencies.' . $this->code . '.symbol');
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
            return config('money.currencies.' . $this->code . '.symbol_first');
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
            return config('money.currencies.' . $this->code . '.decimal_mark');
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
            return config('money.currencies.' . $this->code . '.thousands_separator');
        }

        return $value;
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('currencies.edit', $this->id),
            'permission' => 'update-settings-currencies',
            'attributes' => [
                'id' => 'index-line-actions-edit-currency-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'currencies.destroy',
            'permission' => 'delete-settings-currencies',
            'attributes' => [
                'id' => 'index-line-actions-delete-currency-' . $this->id,
            ],
            'model' => $this,
        ];

        return $actions;
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
