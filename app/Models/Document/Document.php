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
    public const BILL_TYPE = 'bill';

    protected $table = 'documents';

    protected $appends = ['attachment', 'amount_without_tax', 'discount', 'paid', 'received_at', 'status_label', 'sent_at', 'reconciled'];

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
        'notes',
        'footer',
        'parent_id',
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

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy('issued_at', 'desc');
    }

    public function scopeNumber(Builder $query, string $number)
    {
        return $query->where('document_number', '=', $number);
    }

    public function scopeDue($query, $date)
    {
        return $query->whereDate('due_at', '=', $date);
    }

    public function scopeAccrued($query)
    {
        return $query->whereNotIn('status', ['draft', 'cancelled']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', '=', 'paid');
    }

    public function scopeNotPaid($query)
    {
        return $query->where('status', '<>', 'paid');
    }

    public function scopeType(Builder $query, string $type)
    {
        return $query->where($this->table . '.type', '=', $type);
    }

    public function scopeInvoice(Builder $query)
    {
        return $query->where($this->table . '.type', '=', self::INVOICE_TYPE);
    }

    public function scopeBill(Builder $query)
    {
        return $query->where($this->table . '.type', '=', self::BILL_TYPE);
    }

    /**
     * @inheritDoc
     *
     * @param  Document $src
     * @param  boolean $child
     */
    public function onCloning($src, $child = null)
    {
        $this->status          = 'draft';
        $this->document_number = $this->getNextDocumentNumber($src->type);
    }

    public function getSentAtAttribute(string $value = null)
    {
        $sent = $this->histories()->where('status', 'sent')->first();

        return $sent->created_at ?? null;
    }

    public function getReceivedAtAttribute(string $value = null)
    {
        $received = $this->histories()->where('status', 'received')->first();

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
        switch ($this->status) {
            case 'paid':
                $label = 'success';
                break;
            case 'partial':
                $label = 'info';
                break;
            case 'sent':
            case 'received':
                $label = 'danger';
                break;
            case 'viewed':
                $label = 'warning';
                break;
            case 'cancelled':
                $label = 'dark';
                break;
            default:
                $label = 'primary';
                break;
        }

        return $label;
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

    protected static function newFactory(): Factory
    {
        return DocumentFactory::new();
    }
}
