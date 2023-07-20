<?php

namespace App\Models\Document;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Znck\Eloquent\Traits\BelongsToThrough;

class DocumentItemTax extends Model
{
    use Cloneable, Currencies, BelongsToThrough;

    protected $table = 'document_item_taxes';

    protected $fillable = ['company_id', 'type', 'document_id', 'document_item_id', 'tax_id', 'name', 'amount', 'created_from', 'created_by'];

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document')->withoutGlobalScope('App\Scopes\Document');
    }

    public function item()
    {
        return $this->belongsToThrough('App\Models\Common\Item', 'App\Models\Document\DocumentItem', 'document_item_id')->withDefault(['name' => trans('general.na')]);
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax')->withDefault(['name' => trans('general.na'), 'rate' => 0]);
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

    public function onCloned($src)
    {
        $document_item = DocumentItem::find($this->document_item_id);

        $this->update(['document_id' => $document_item->document_id]);
    }
}
