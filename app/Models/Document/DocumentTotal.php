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

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document')->withoutGlobalScope('App\Scopes\Document');
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
                $item_tax = $this->getChargedItemTax();

                // Still needed below to tell a fixed tax from a percentage one
                $tax = $item_tax ? Tax::find($item_tax->tax_id) : Tax::where('name', $title)->first();

                // Prefer the rate this document was charged at, so changing a tax
                // rate later does not rewrite what an existing document shows.
                $percent = $item_tax?->rate;

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

    /**
     * The line tax this tax total was made from.
     *
     * A total keeps only the tax name, and two taxes can share a name, so the name
     * alone can point at the other tax. Totals are written one per tax, in the order
     * the taxes first appear on the lines (CreateDocumentItemsAndTotals), so the n-th
     * total of a name belongs to the n-th tax of that name on the lines.
     */
    protected function getChargedItemTax(): ?DocumentItemTax
    {
        $item_taxes = DocumentItemTax::where('document_id', $this->document_id)
            ->where('name', $this->name)
            ->orderBy('id')
            ->get(['id', 'tax_id', 'rate'])
            ->unique('tax_id')
            ->values();

        if ($item_taxes->count() < 2) {
            return $item_taxes->first();
        }

        $position = static::where('document_id', $this->document_id)
            ->where('code', 'tax')
            ->where('name', $this->name)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->search($this->id);

        return $item_taxes->get((int) $position) ?? $item_taxes->first();
    }
}
