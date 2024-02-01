<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Traits\Recurring as RecurringTrait;
use Illuminate\Database\Eloquent\Builder;

class Recurring extends Model
{
    use RecurringTrait;

    public const ACTIVE_STATUS = 'active';
    public const END_STATUS = 'ended';
    public const COMPLETE_STATUS = 'completed';
    public const INVOICE_RECURRING_TYPE = 'invoice-recurring';
    public const BILL_RECURRING_TYPE = 'bill-recurring';
    public const INCOME_RECURRING_TYPE = 'income-recurring';
    public const EXPENSE_RECURRING_TYPE = 'expense-recurring';

    protected $table = 'recurring';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'recurable_id',
        'recurable_type',
        'frequency',
        'interval',
        'started_at',
        'status',
        'limit_by',
        'limit_count',
        'limit_date',
        'auto_send',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'auto_send'     => 'boolean',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Get all of the owning recurable models.
     */
    public function recurable()
    {
        return $this->morphTo()->isRecurring();
    }

    public function documents()
    {
        return $this->morphedByMany(
            'App\Models\Document\Document',
            'recurable',
            'recurring',
            'recurable_id',
            'id'
        );
    }

    public function invoices()
    {
        return $this->documents()->where('type', self::INVOICE_RECURRING_TYPE);
    }

    public function bills()
    {
        return $this->documents()->where('type', self::BILL_RECURRING_TYPE);
    }

    public function transactions()
    {
        return $this->morphedByMany(
            'App\Models\Banking\Transaction',
            'recurable',
            'recurring',
            'recurable_id',
            'id'
        );
    }

    public function incomes()
    {
        return $this->transactions()->where('type', self::INCOME_RECURRING_TYPE);
    }

    public function expenses()
    {
        return $this->transactions()->where('type', self::EXPENSE_RECURRING_TYPE);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', static::ACTIVE_STATUS);
    }

    public function scopeEnded(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', static::END_STATUS);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', static::COMPLETE_STATUS);
    }

    public function scopeDocument(Builder $query, $type): Builder
    {
        return $query->where($this->qualifyColumn('recurable_type'), '=', 'App\\Models\\Document\\Document')
            ->whereHas('recurable', function (Builder $query) use ($type) {
                $query->where('type', $type);
            });
    }

    public function scopeInvoice(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('recurable_type'), '=', 'App\\Models\\Document\\Document')
            ->whereHas('recurable', function (Builder $query) {
                $query->where('type', self::INVOICE_RECURRING_TYPE);
            })
            ->orWhereDoesntHave('recurable');
    }

    public function scopeBill(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('recurable_type'), '=', 'App\\Models\\Document\\Document')
            ->whereHas('recurable', function (Builder $query) {
                $query->where('type', self::BILL_RECURRING_TYPE);
            })
            ->orWhereDoesntHave('recurable');
    }

    public function scopeTransaction(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('recurable_type'), '=', 'App\\Models\\Banking\\Transaction');
    }

    public function scopeExpenseTransaction(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('recurable_type'), '=', 'App\\Models\\Banking\\Transaction')
            ->whereHas('recurable', function (Builder $query) {
                $query->where('type', self::EXPENSE_RECURRING_TYPE);
            })
            ->orWhereDoesntHave('recurable');
    }

    public function scopeIncomeTransaction(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('recurable_type'), '=', 'App\\Models\\Banking\\Transaction')
            ->whereHas('recurable', function (Builder $query) {
                $query->where('type', self::INCOME_RECURRING_TYPE);
            })
            ->orWhereDoesntHave('recurable');
    }

    /**
     * Determine if recurring is a document.
     *
     * @return bool
     */
    public function isDocument()
    {
        return (bool) ($this->recurable_type == 'App\\Models\\Document\\Document');
    }

    /**
     * Determine if recurring is a transaction.
     *
     * @return bool
     */
    public function isTransaction()
    {
        return (bool) ($this->recurable_type == 'App\\Models\\Banking\\Transaction');
    }
}
