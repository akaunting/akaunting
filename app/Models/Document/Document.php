<?php

namespace App\Models\Document;

use App\Abstracts\Model;
use App\Models\Common\Media as MediaModel;
use App\Models\Setting\Tax;
use App\Scopes\Document as Scope;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Documents;
use App\Traits\Media;
use App\Traits\Recurring;
use Bkwld\Cloner\Cloneable;
use Database\Factories\Document as DocumentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, Documents, Cloneable, Currencies, DateTime, Media, Recurring;

    public const INVOICE_TYPE = 'invoice';
    public const INVOICE_RECURRING_TYPE = 'invoice-recurring';
    public const BILL_TYPE = 'bill';
    public const BILL_RECURRING_TYPE = 'bill-recurring';

    protected $table = 'documents';

    protected $appends = ['attachment', 'amount_without_tax', 'discount', 'paid', 'received_at', 'status_label', 'sent_at', 'reconciled', 'contact_location'];

    protected $dates = ['deleted_at', 'issued_at', 'due_at'];

    protected $fillable = [
        'company_id',
        'type',
        'document_number',
        'order_number',
        'status',
        'issued_at',
        'due_at',
        'amount',
        'currency_code',
        'currency_rate',
        'category_id',
        'contact_id',
        'contact_name',
        'contact_email',
        'contact_tax_number',
        'contact_phone',
        'contact_address',
        'contact_country',
        'contact_state',
        'contact_zip_code',
        'contact_city',
        'notes',
        'footer',
        'parent_id',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'currency_rate' => 'double',
    ];

    /**
     * @var array
     */
    public $sortable = ['document_number', 'contact_name', 'amount', 'status', 'issued_at', 'due_at'];

    /**
     * @var array
     */
    public $cloneable_relations = ['items', 'recurring', 'totals'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new Scope);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function children()
    {
        return $this->hasMany('App\Models\Document\Document', 'parent_id');
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na')]);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Document\DocumentItem', 'document_id');
    }

    public function item_taxes()
    {
        return $this->hasMany('App\Models\Document\DocumentItemTax', 'document_id');
    }

    public function histories()
    {
        return $this->hasMany('App\Models\Document\DocumentHistory', 'document_id');
    }

    public function last_history()
    {
        return $this->hasOne('App\Models\Document\DocumentHistory', 'document_id')->latest()->withDefault([
            'description' => trans('messages.success.added', ['type' => $this->document_number]),
            'created_at' => $this->created_at
        ]);
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Document\Document', 'parent_id')->isRecurring();
    }

    public function payments()
    {
        return $this->transactions();
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function totals()
    {
        return $this->hasMany('App\Models\Document\DocumentTotal', 'document_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'document_id');
    }

    public function totals_sorted()
    {
        return $this->totals()->orderBy('sort_order');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('issued_at', 'desc');
    }

    public function scopeNumber(Builder $query, string $number): Builder
    {
        return $query->where('document_number', '=', $number);
    }

    public function scopeDue(Builder $query, $date): Builder
    {
        return $query->whereDate('due_at', '=', $date);
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', $status);
    }

    public function scopeAccrued(Builder $query): Builder
    {
        return $query->whereNotIn($this->qualifyColumn('status'), ['draft', 'cancelled']);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', 'paid');
    }

    public function scopeNotPaid(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '<>', 'paid');
    }

    public function scopeFuture(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('status'), $this->getDocumentStatusesForFuture());
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', $type);
    }

    public function scopeInvoice(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::INVOICE_TYPE);
    }

    public function scopeInvoiceRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::INVOICE_RECURRING_TYPE);
    }

    public function scopeBill(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::BILL_TYPE);
    }

    public function scopeBillRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::BILL_RECURRING_TYPE);
    }

    /**
     * @inheritDoc
     *
     * @param  Document $src
     * @param  boolean $child
     */
    public function onCloning($src, $child = null)
    {
        if (app()->has(\App\Console\Commands\RecurringCheck::class)) {
            $type = $this->getRealTypeOfRecurringDocument($src->type);
        } else {
            $type = $src->type;
        }

        $this->status          = 'draft';
        $this->document_number = $this->getNextDocumentNumber($type);
    }

    public function getSentAtAttribute(string $value = null)
    {
        $sent = $this->histories()->where('document_histories.status', 'sent')->first();

        return $sent->created_at ?? null;
    }

    public function getReceivedAtAttribute(string $value = null)
    {
        $received = $this->histories()->where('document_histories.status', 'received')->first();

        return $received->created_at ?? null;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getAttachmentAttribute($value = null)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->all();
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
     * Get the discount percentage.
     *
     * @return string
     */
    public function getDiscountAttribute()
    {
        $percent = 0;

        $discount = $this->totals->where('code', 'discount')->makeHidden('title')->pluck('amount')->first();

        if ($discount) {
            $sub_total = $this->totals->where('code', 'sub_total')->makeHidden('title')->pluck('amount')->first();

            $percent = number_format((($discount * 100) / $sub_total), 0);
        }

        return $percent;
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

        $code = $this->currency_code;
        $rate = $this->currency_rate;
        $precision = config('money.' . $code . '.precision');

        if ($this->transactions->count()) {
            foreach ($this->transactions as $transaction) {
                $amount = $transaction->amount;

                if ($code != $transaction->currency_code) {
                    $amount = $this->convertBetween($amount, $transaction->currency_code, $transaction->currency_rate, $code, $rate);
                }

                $paid += $amount;
            }
        }

        return round($paid, $precision);
    }

    /**
     * Get the reconcilation status.
     *
     * @return integer
     */
    public function getReconciledAttribute()
    {
        if (empty($this->amount)) {
            return 0;
        }

        $reconciled = $reconciled_amount = 0;

        $code = $this->currency_code;
        $rate = $this->currency_rate;
        $precision = config('money.' . $code . '.precision');

        if ($this->transactions->count()) {
            foreach ($this->transactions as $transaction) {
                $amount = $transaction->amount;

                if ($code != $transaction->currency_code) {
                    $amount = $this->convertBetween($amount, $transaction->currency_code, $transaction->currency_rate, $code, $rate);
                }

                if ($transaction->reconciled) {
                    $reconciled_amount = +$amount;
                }
            }
        }

        if (bccomp(round($this->amount, $precision), round($reconciled_amount, $precision), $precision) === 0) {
            $reconciled = 1;
        }

        return $reconciled;
    }

    /**
     * Get the not paid amount.
     *
     * @return string
     */
    public function getAmountDueAttribute()
    {
        $precision = config('money.' . $this->currency_code . '.precision');

        return round($this->amount - $this->paid, $precision);
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'paid'      => 'status-success',
            'partial'   => 'status-partial',
            'sent'      => 'status-danger',
            'received'  => 'status-danger',
            'viewed'    => 'status-sent',
            'cancelled' => 'status-canceled',
            default     => 'status-draft',
        };
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
     * Get the amount without tax.
     *
     * @return string
     */
    public function getAmountWithoutTaxAttribute()
    {
        $amount = $this->amount;

        $this->totals->where('code', 'tax')->each(function ($total) use(&$amount) {
            $tax = Tax::name($total->name)->first();

            if (!empty($tax) && ($tax->type == 'withholding')) {
                return;
            }

            $amount -= $total->amount;
        });

        return $amount;
    }

    public function getTemplatePathAttribute($value = null)
    {
        return $value ?: 'sales.invoices.print_' . setting('invoice.template');
    }

    public function getContactLocationAttribute()
    {
        $location = [];

        if ($this->contact_city) {
            $location[] = $this->contact_city;
        }

        if ($this->contact_zip_code) {
            $location[] = $this->contact_zip_code;
        }

        if ($this->contact_state) {
            $location[] = $this->contact_state;
        }

        if ($this->contact_country) {
            $location[] = trans('countries.' . $this->contact_country);
        }

        return implode(', ', $location);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $group = config('type.document.' . $this->type . '.group');
        $prefix = config('type.document.' . $this->type . '.route.prefix');
        $permission_prefix = config('type.document.' . $this->type . '.permission.prefix');
        $translation_prefix = config('type.document.' . $this->type . '.translation.prefix');

        if (empty($prefix)) {
            return $actions;
        }

        try {
            $actions[] = [
                'title' => trans('general.show'),
                'icon' => 'visibility',
                'url' => route($prefix . '.show', $this->id),
                'permission' => 'read-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-more-actions-show-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            if (! $this->reconciled) {
                $actions[] = [
                    'title' => trans('general.edit'),
                    'icon' => 'edit',
                    'url' => route($prefix . '.edit', $this->id),
                    'permission' => 'update-' . $group . '-' . $permission_prefix,
                    'attributes' => [
                        'id' => 'index-more-actions-edit-' . $this->id,
                    ],
                ];
            }
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.duplicate'),
                'icon' => 'file_copy',
                'url' => route($prefix . '.duplicate', $this->id),
                'permission' => 'create-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-more-actions-duplicate-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        if ($this->status != 'cancelled') {
            try {
                $actions[] = [
                    'title' => trans('general.print'),
                    'icon' => 'print',
                    'url' => route($prefix . '.print', $this->id),
                    'permission' => 'read-' . $group . '-' . $permission_prefix,
                    'attributes' => [
                        'id' => 'index-more-actions-print-' . $this->id,
                        'target' => '_blank',
                    ],
                ];
            } catch (\Exception $e) {}
        }

        try {
            $actions[] = [
                'title' => trans('general.download_pdf'),
                'icon' => 'picture_as_pdf',
                'url' => route($prefix . '.pdf', $this->id),
                'permission' => 'read-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-more-actions-pdf-' . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        if (! str_contains($this->type, 'recurring')) {
            if ($this->status != 'cancelled') {
                $actions[] = [
                    'type' => 'divider',
                ];

                try {
                    $actions[] = [
                        'type' => 'button',
                        'title' => trans('general.share_link'),
                        'icon' => 'share',
                        'url' => route('modals.'. $prefix . '.share.create', $this->id),
                        'permission' => 'read-' . $group . '-' . $permission_prefix,
                        'attributes' => [
                            'id' => 'index-more-actions-share-link-' . $this->id,
                            '@click' => 'onShareLink("' . route('modals.'. $prefix . '.share.create', $this->id) . '")',
                        ],
                    ];
                } catch (\Exception $e) {}

                try {
                    if (! empty($this->contact) && $this->contact->email && $this->type == 'invoice') {
                        $actions[] = [
                            'type' => 'button',
                            'title' => trans('invoices.send_mail'),
                            'icon' => 'email',
                            'url' => route('modals.'. $prefix . '.emails.create', $this->id),
                            'permission' => 'read-' . $group . '-' . $permission_prefix,
                            'attributes' => [
                                'id'        => 'index-more-actions-send-email-' . $this->id,
                                '@click'    => 'onEmail("' . route('modals.'. $prefix . '.emails.create', $this->id) . '")',
                            ],
                        ];
                    }
                } catch (\Exception $e) {}
            }

            $actions[] = [
                'type' => 'divider',
            ];

            if ($this->status != 'cancelled') {
                try {
                    $actions[] = [
                        'title' => trans('general.cancel'),
                        'icon' => 'cancel',
                        'url' => route($prefix . '.cancelled', $this->id),
                        'permission' => 'update-' . $group . '-' . $permission_prefix,
                        'attributes' => [
                            'id' => 'index-more-actions-cancel-' . $this->id,
                        ],
                    ];
                } catch (\Exception $e) {}

                $actions[] = [
                    'type' => 'divider',
                ];
            }

            try {
                $actions[] = [
                    'type' => 'delete',
                    'icon' => 'delete',
                    'title' => $translation_prefix,
                    'route' => $prefix . '.destroy',
                    'permission' => 'delete-' . $group . '-' . $permission_prefix,
                    'model' => $this,
                ];
            } catch (\Exception $e) {}
        } else {
            try {
                $actions[] = [
                    'title' => trans('general.end'),
                    'icon' => 'block',
                    'url' => route($prefix. '.end', $this->id),
                    'permission' => 'update-' . $group . '-' . $permission_prefix,
                ];
            } catch (\Exception $e) {}
        }

        return $actions;
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

        if (request()->route()->hasParameter('recurring_invoice')) {
            $query->invoiceRecurring();
        }

        if (request()->route()->hasParameter('recurring_bill')) {
            $query->billRecurring();
        }

        return $query->firstOrFail();
    }

    protected static function newFactory(): Factory
    {
        return DocumentFactory::new();
    }
}
