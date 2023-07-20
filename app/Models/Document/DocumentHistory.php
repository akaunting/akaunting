<?php

namespace App\Models\Document;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentHistory extends Model
{
    use Currencies;

    protected $table = 'document_histories';

    protected $fillable = ['company_id', 'type', 'document_id', 'status', 'notify', 'description', 'created_from', 'created_by'];

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
}
