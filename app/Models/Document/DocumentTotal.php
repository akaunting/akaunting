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

    protected $fillable = ['company_id', 'type', 'document_id', 'code', 'name', 'amount', 'sort_order'];

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
                $percent = $this->document->discount;

                break;
            case 'tax':
                $tax = Tax::where('name', $title)->first();

                if (!empty($tax->rate)) {
                    $percent = $tax->rate;
                }

                break;
        }

        if (!empty($percent)) {
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
