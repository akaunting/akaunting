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
                $tax = Tax::where('name', $title)->first();

                if (! empty($tax->rate)) {
                    $percent = $tax->rate;
                }

                break;
        }

        if (! empty($percent)) {
            $title .= ' (';

            if (setting('localisation.percent_position', 'after') === 'after') {
                $title .= ($this->code === 'discount') ? $percent . '%' : (($tax->type === 'fixed') ? $percent : $percent . '%');
            } else {
                $title .= ($this->code === 'discount') ? '%' . $percent : (($tax->type === 'fixed') ? $percent : '%' . $percent);
            }

            $title .= ')';
        }

        return $title;
    }
}
