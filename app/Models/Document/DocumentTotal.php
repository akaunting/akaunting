<?php

namespace App\Models\Document;

use App\Abstracts\Model;
use App\Models\Setting\Tax;
use App\Traits\DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTotal extends Model
{
    use DateTime;

    protected $table = 'document_totals';

    protected $appends = ['title'];

    protected $fillable = ['company_id', 'type', 'document_id', 'code', 'name', 'amount', 'sort_order', 'created_from', 'created_by'];

    /**
     * Only there for the title, which is serialized with every total.
     */
    protected $hidden = ['item_taxes'];

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document')->withoutGlobalScope('App\Scopes\Document');
    }

    /**
     * The item taxes of the same document, where the title reads the rate a tax was charged at.
     * A list eager loads them, so it does not query once per tax total.
     */
    public function item_taxes()
    {
        return $this->hasMany('App\Models\Document\DocumentItemTax', 'document_id', 'document_id');
    }

    public function scopeType(Builder $query, string $type)
    {
        return $query->where($this->qualifyColumn('type'), '=', $type);
    }

    public function scopeInvoice(Builder $query)
    {
        return $query->where($this->qualifyColumn('type'), '=', Document::INVOICE_TYPE);
    }

    public function scopeInvoiceRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Document::INVOICE_RECURRING_TYPE)
                    ->whereHas('document.recurring', function (Builder $query) {
                        $query->whereNull('deleted_at');
                    });
    }

    public function scopeBill(Builder $query)
    {
        return $query->where($this->qualifyColumn('type'), '=', Document::BILL_TYPE);
    }

    public function scopeBillRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Document::BILL_RECURRING_TYPE)
                    ->whereHas('document.recurring', function (Builder $query) {
                        $query->whereNull('deleted_at');
                    });
    }

    public function scopeCode($query, $code)
    {
        return $query->where('code', '=', $code);
    }

    public function getTitleAttribute()
    {
        $title = $this->name;

        $percent = 0;

        $tax = null;

        switch ($this->code) {
            case 'discount':
                $title = trans($title);
                $percent = ($this->document->discount_rate && $this->document->discount_type == 'percentage') ? $this->document->discount_rate : 0;

                break;
            case 'tax':
                // Still needed below to tell a fixed tax from a percentage one
                $tax = Tax::where('name', $title)->first();

                // Prefer the rate this document was charged at, so changing a tax
                // rate later does not rewrite what an existing document shows.
                $percent = $this->relationLoaded('item_taxes')
                    ? $this->item_taxes->firstWhere('name', $title)?->rate
                    : DocumentItemTax::where('document_id', $this->document_id)
                        ->where('name', $title)
                        ->value('rate');

                if (empty($percent) && ! empty($tax->rate)) {
                    $percent = $tax->rate;
                }

                break;
        }

        if (! empty($percent)) {
            $title .= ' (';

            if (setting('localisation.percent_position', 'after') === 'after') {
                $title .= ($this->code === 'discount') ? $percent . '%' : (($tax?->type === 'fixed') ? $percent : $percent . '%');
            } else {
                $title .= ($this->code === 'discount') ? '%' . $percent : (($tax?->type === 'fixed') ? $percent : '%' . $percent);
            }

            $title .= ')';
        }

        return $title;
    }
}
