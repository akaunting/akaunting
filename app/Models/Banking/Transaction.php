<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Common\Media as MediaModel;
use App\Scopes\Transaction as Scope;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Media;
use App\Traits\Recurring;
use App\Traits\Transactions;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use Cloneable, Currencies, DateTime, HasFactory, Media, Recurring, Transactions;

    public const INCOME_TYPE = 'income';
    public const INCOME_TRANSFER_TYPE = 'income-transfer';
    public const INCOME_SPLIT_TYPE = 'income-split';
    public const INCOME_RECURRING_TYPE = 'income-recurring';
    public const EXPENSE_TYPE = 'expense';
    public const EXPENSE_TRANSFER_TYPE = 'expense-transfer';
    public const EXPENSE_SPLIT_TYPE = 'expense-split';
    public const EXPENSE_RECURRING_TYPE = 'expense-recurring';

    protected $table = 'transactions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'type',
        'number',
        'account_id',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'document_id',
        'contact_id',
        'description',
        'category_id',
        'payment_method',
        'reference',
        'parent_id',
        'split_id',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paid_at'           => 'datetime',
        'amount'            => 'double',
        'currency_rate'     => 'double',
        'deleted_at'        => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = [
        'paid_at',
        'number',
        'type',
        'account.name',
        'contact.name',
        'category.name',
        'document.document_number',
        'amount',
        'recurring.started_at',
        'recurring.status',
    ];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['recurring', 'taxes'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new Scope);
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }

    public function bill()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id')->withoutGlobalScope('App\Scopes\Document');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withoutGlobalScope('App\Scopes\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function children()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'parent_id');
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na')]);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id')->withoutGlobalScope('App\Scopes\Document');
    }

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id')->withoutGlobalScope('App\Scopes\Document');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'parent_id')->isRecurring();
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function transfer()
    {
        if ($this->type == static::INCOME_TRANSFER_TYPE) {
            return $this->belongsTo('App\Models\Banking\Transfer', 'id', 'income_transaction_id');
        }

        if ($this->type == static::EXPENSE_TRANSFER_TYPE) {
            return $this->belongsTo('App\Models\Banking\Transfer', 'id', 'expense_transaction_id');
        }

        return null;
    }

    public function splits()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'split_id');
    }

    public function user()
    {
        return $this->belongsTo(user_model_class(), 'contact_id', 'id');
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Banking\TransactionTax');
    }

    public function scopeNumber(Builder $query, string $number): Builder
    {
        return $query->where('number', '=', $number);
    }

    public function scopeType(Builder $query, $types): Builder
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    public function scopeIncome(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getIncomeTypes());
    }

    public function scopeIncomeTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::INCOME_TRANSFER_TYPE);
    }

    public function scopeIncomeRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::INCOME_RECURRING_TYPE)
                ->whereHas('recurring', function (Builder $query) {
                    $query->whereNull('deleted_at');
                });
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getExpenseTypes());
    }

    public function scopeExpenseTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::EXPENSE_TRANSFER_TYPE);
    }

    public function scopeExpenseRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::EXPENSE_RECURRING_TYPE)
                ->whereHas('recurring', function (Builder $query) {
                    $query->whereNull('deleted_at');
                });
    }

    public function scopeIsTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-transfer');
    }

    public function scopeIsNotTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-transfer');
    }

    public function scopeIsRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-recurring');
    }

    public function scopeIsNotRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-recurring');
    }

    public function scopeIsSplit(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-split');
    }

    public function scopeIsNotSplit(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-split');
    }

    public function scopeIsDocument(Builder $query): Builder
    {
        return $query->whereNotNull('document_id');
    }

    public function scopeIsNotDocument(Builder $query): Builder
    {
        return $query->whereNull('document_id');
    }

    public function scopeDocumentId(Builder $query, int $document_id): Builder
    {
        return $query->where('document_id', '=', $document_id);
    }

    public function scopeAccountId(Builder $query, int $account_id): Builder
    {
        return $query->where('account_id', '=', $account_id);
    }

    public function scopeContactId(Builder $query, int $contact_id): Builder
    {
        return $query->where('contact_id', '=', $contact_id);
    }

    public function scopeCategoryId(Builder $query, int $category_id): Builder
    {
        return $query->where('category_id', '=', $category_id);
    }

    /**
     * Order by paid date.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->sum('amount');
    }

    public function scopeIsReconciled(Builder $query): Builder
    {
        return $query->where('reconciled', 1);
    }

    public function scopeIsNotReconciled(Builder $query): Builder
    {
        return $query->where('reconciled', 0);
    }

    public function onCloning($src, $child = null)
    {
        if (app()->has(\App\Console\Commands\RecurringCheck::class)) {
            $suffix = '';
        } else {
            $suffix = $src->isRecurringTransaction() ? '-recurring' : '';
        }

        $this->number       = $this->getNextTransactionNumber($this->type, $suffix);
        $this->document_id  = null;
        $this->split_id     = null;
        unset($this->reconciled);
    }

    /**
     * Convert amount to double.
     *
     * @return float
     */
    public function getAmountForAccountAttribute()
    {
        $amount = $this->amount;

        // Convert amount if not same currency
        if ($this->account->currency_code != $this->currency_code) {
            $to_code = $this->account->currency_code;
            $to_rate = currency($this->account->currency_code)->getRate();

            $amount = $this->convertBetween($amount, $this->currency_code, $this->currency_rate, $to_code, $to_rate);
        }

        return $amount;
    }

    /**
     * Convert amount to double.
     *
     * @return float
     */
    public function getAmountForDocumentAttribute()
    {
        $amount = $this->amount;

        // Convert amount if not same currency
        if ($this->document->currency_code != $this->currency_code) {
            $to_code = $this->document->currency_code;
            $to_rate = $this->document->currency_rate;
            //$to_rate = currency($this->document->currency_code)->getRate();

            $amount = $this->convertBetween($amount, $this->currency_code, $this->currency_rate, $to_code, $to_rate);
        }

        return $amount;
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

        return $this->getMedia('attachment')->all();
    }

    /**
     * Get the splittable status.
     *
     * @return bool
     */
    public function getIsSplittableAttribute()
    {
        return is_null($this->split_id);
    }

    public function delete_attachment()
    {
        if ($attachments = $this->attachment) {
            foreach ($attachments as $file) {
                MediaModel::where('id', $file->id)->delete();
            }
        }
    }

    /**
     * Get the title of type.
     *
     * @return string
     */
    public function getTypeTitleAttribute($value)
    {
        $type = $this->getRealTypeOfRecurringTransaction($this->type);
        $type = $this->getRealTypeOfTransferTransaction($type);
        $type = $this->getRealTypeOfSplitTransaction($type);

        $type = str_replace('-', '_', $type);

        return $value ?? trans_choice('general.' . Str::plural($type), 1);
    }

    /**
     * Get the item id.
     *
     * @return string
     */
    public function getTaxIdsAttribute()
    {
        return $this->taxes()->pluck('tax_id');
    }

    /**
     * Get the amount before tax.
     *
     * @return string
     */
    public function getTotalTaxAttribute()
    {
        $precision = currency($this->currency_code)->getPrecision();

        $amount = 0;

        if ($this->taxes->count()) {
            foreach ($this->taxes as $tax) {
                $amount += $tax->amount;
            }
        }

        return round($amount, $precision);
    }

    /**
     * Get the amount before tax.
     *
     * @return string
     */
    public function getAmountBeforeTaxAttribute()
    {
        if (empty($this->amount)) {
            return false;
        }

        $precision = currency($this->currency_code)->getPrecision();

        return round($this->amount - $this->total_tax, $precision);
    }

    /**
     * Get the route name.
     *
     * @return string
     */
    public function getRouteNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->isIncome()) {
            if (! empty($this->document_id) && $this->document->type != 'invoice') {
                return $this->getRouteFromConfig();
            } else {
                return !empty($this->document_id) ? 'invoices.show' : 'transactions.show';
            }
        }

        if ($this->isExpense()) {
            if (! empty($this->document_id) && $this->document->type != 'bill') {
                return $this->getRouteFromConfig();
            } else {
                return !empty($this->document_id) ? 'bills.show' : 'transactions.show';
            }
        }

        return 'transactions.index';
    }

    public function getRouteFromConfig()
    {
        $route = '';

        $alias = config('type.document.' . $this->document->type . '.alias');
        $prefix = config('type.document.' . $this->document->type . '.route.prefix');

        // if use module set module alias
        if (!empty($alias)) {
            $route .= $alias . '.';
        }

        if (!empty($prefix)) {
            $route .= $prefix . '.';
        }

        if ($route) {
            return $route . 'show';
        }

        return 'transactions.index';
    }

    /**
     * Get the route id.
     *
     * @return string
     */
    public function getRouteIdAttribute($value)
    {
        return !empty($value) ? $value : (!empty($this->document_id) ? $this->document_id : $this->id);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $prefix = 'transactions';

        if (Str::contains($this->type, 'recurring')) {
            $prefix = 'recurring-transactions';
        }

        try {
            $actions[] = [
                'title' => trans('general.show'),
                'icon' => 'visibility',
                'url' => route($prefix. '.show', $this->id),
                'permission' => 'read-banking-transactions',
                'attributes' => [
                    'id' => 'index-line-actions-show-' . $this->type . '-'  . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            if (! $this->reconciled && $this->isNotTransferTransaction()) {
                $actions[] = [
                    'title' => trans('general.edit'),
                    'icon' => 'edit',
                    'url' => route($prefix. '.edit', $this->id),
                    'permission' => 'update-banking-transactions',
                    'attributes' => [
                        'id' => 'index-line-actions-edit-' . $this->type . '-'  . $this->id,
                    ],
                ];
            }
        } catch (\Exception $e) {}

        try {
            if (empty($this->document_id) 
                && $this->isNotTransferTransaction()
                && $this->isNotSplitTransaction()
            ) {
                $actions[] = [
                    'title' => trans('general.duplicate'),
                    'icon' => 'file_copy',
                    'url' => route($prefix. '.duplicate', $this->id),
                    'permission' => 'create-banking-transactions',
                    'attributes' => [
                        'id' => 'index-line-actions-duplicate-' . $this->type . '-'  . $this->id,
                    ],
                ];
            }
        } catch (\Exception $e) {}

        try {
            if (
                $this->is_splittable
                && empty($this->document_id)
                && empty($this->recurring)
                && $this->isNotTransferTransaction()
            ) {
                $connect = [
                    'type' => 'button',
                    'title' => trans('general.connect'),
                    'icon' => 'sensors',
                    'permission' => 'create-banking-transactions',
                    'attributes' => [
                        'id' => 'index-line-actions-connect-' . $this->type . '-'  . $this->id,
                        '@click' => 'onConnectTransactions(\'' . route('transactions.dial', $this->id) . '\')',
                    ],
                ];

                $actions[] = $connect;

                $actions[] = [
                    'type' => 'divider',
                ];
            }
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.print'),
                'icon' => 'print',
                'url' => route($prefix. '.print', $this->id),
                'permission' => 'read-banking-transactions',
                'attributes' => [
                    'id' => 'index-line-actions-print-' . $this->type . '-'  . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.download_pdf'),
                'icon' => 'picture_as_pdf',
                'url' => route($prefix. '.pdf', $this->id),
                'permission' => 'read-banking-transactions',
                'attributes' => [
                    'id' => 'index-line-actions-pdf-' . $this->type . '-'  . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        if ($prefix != 'recurring-transactions') {
            if ($this->isNotTransferTransaction()) {
                $actions[] = [
                    'type' => 'divider',
                ];

                try {
                    $actions[] = [
                        'type' => 'button',
                        'title' => trans('general.share_link'),
                        'icon' => 'share',
                        'url' => route('modals.transactions.share.create', $this->id),
                        'permission' => 'read-banking-transactions',
                        'attributes' => [
                            'id' => 'index-line-actions-share-' . $this->type . '-'  . $this->id,
                            '@click' => 'onShareLink("' . route('modals.transactions.share.create', $this->id) . '")',
                        ],
                    ];
                } catch (\Exception $e) {}

                try {
                    if (! empty($this->contact) && $this->contact->email) {
                        $actions[] = [
                            'type' => 'button',
                            'title' => trans('invoices.send_mail'),
                            'icon' => 'email',
                            'url' => route('modals.transactions.emails.create', $this->id),
                            'permission' => 'read-banking-transactions',
                            'attributes' => [
                                'id' => 'index-line-actions-send-email-' . $this->type . '-'  . $this->id,
                                '@click' => 'onSendEmail("' . route('modals.transactions.emails.create', $this->id) . '")',
                            ],
                        ];
                    }
                } catch (\Exception $e) {}

                $actions[] = [
                    'type' => 'divider',
                ];

                try {
                    if (! $this->reconciled) {
                        $actions[] = [
                            'type' => 'delete',
                            'icon' => 'delete',
                            'title' => ! empty($this->recurring) ? 'transactions' : 'recurring_template',
                            'route' => $prefix. '.destroy',
                            'permission' => 'delete-banking-transactions',
                            'model-name' => 'number',
                            'attributes' => [
                                'id' => 'index-line-actions-delete-' . $this->type . '-'  . $this->id,
                            ],
                            'model' => $this,
                        ];
                    }
                } catch (\Exception $e) {}
            }
        } else {
            if ($this->recurring && $this->recurring->status != 'ended') {
                try {
                    $actions[] = [
                        'title' => trans('general.end'),
                        'icon' => 'block',
                        'url' => route($prefix. '.end', $this->id),
                        'permission' => 'update-banking-transactions',
                        'attributes' => [
                            'id' => 'index-line-actions-end-' . $this->type . '-'  . $this->id,
                        ],
                    ];
                } catch (\Exception $e) {}
            }
        }

        return $actions;
    }

    /**
     * Get the recurring status label.
     *
     * @return string
     */
    public function getRecurringStatusLabelAttribute()
    {
        return match($this->recurring->status) {
            'active'    => 'status-partial',
            'ended'     => 'status-success',
            default     => 'status-success',
        };
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $query = $this->where('id', $value);

        if (request()->route()->hasParameter('recurring_transaction')) {
            $query->isRecurring();
        }

        return $query->firstOrFail();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Transaction::new();
    }
}
