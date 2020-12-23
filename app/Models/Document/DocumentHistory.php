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

    protected $fillable = ['company_id', 'type', 'document_id', 'status', 'notify', 'description'];

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document');
    }

    public function scopeType(Builder $query, string $type)
    {
        return $query->where($this->table . '.type', '=', $type);
    }

    public function scopeInvoice(Builder $query)
    {
        return $query->where($this->table . '.type', '=', Document::INVOICE_TYPE);
    }

    public function scopeBill(Builder $query)
    {
        return $query->where($this->table . '.type', '=', Document::BILL_TYPE);
    }
}
