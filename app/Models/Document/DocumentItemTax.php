<?php

namespace App\Models\Document;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Illuminate\Database\Eloquent\Builder;
use Znck\Eloquent\Traits\BelongsToThrough;

class DocumentItemTax extends Model
{
    use Currencies, BelongsToThrough;

    protected $table = 'document_item_taxes';

    protected $fillable = ['company_id', 'type', 'document_id', 'document_item_id', 'tax_id', 'name', 'amount'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
    ];

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document');
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
