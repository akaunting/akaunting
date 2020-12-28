<?php

namespace App\Models\Document;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentItem extends Model
{
    use Cloneable, Currencies;

    protected $table = 'document_items';

    protected $appends = ['discount'];

    protected $fillable = [
        'company_id',
        'type',
        'document_id',
        'item_id',
        'name',
        'description',
        'quantity',
        'price',
        'total',
        'tax',
        'discount_rate',
        'discount_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'double',
        'total' => 'double',
        'tax' => 'double',
    ];

    /**
     * @var array
     */
    public $cloneable_relations = ['taxes'];

    public static function boot()
    {
        parent::boot();

        static::retrieved(
            function ($model) {
                $model->setTaxIds();
            }
        );
    }

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item')->withDefault(['name' => trans('general.na')]);
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Document\DocumentItemTax', 'document_item_id', 'id');
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

    public function getDiscountAttribute(): string
    {
        if (setting('localisation.percent_position', 'after') === 'after') {
            $text = ($this->discount_type === 'normal') ? $this->discount_rate . '%' : $this->discount_rate;
        } else {
            $text = ($this->discount_type === 'normal') ? '%' . $this->discount_rate : $this->discount_rate;
        }

        return $text;
    }

    public function getDiscountRateAttribute(int $value = 0)
    {
        $discount_rate = 0;

        switch (setting('localisation.discount_location', 'total')) {
            case 'no':
            case 'total':
                $discount_rate = 0;
                break;
            case 'both':
            case 'item':
                $discount_rate = $value;
                break;
        }

        return $discount_rate;
    }

    /**
     * Convert tax to Array.
     */
    public function setTaxIds()
    {
        $tax_ids = [];

        foreach ($this->taxes as $tax) {
            $tax_ids[] = (string) $tax->tax_id;
        }

        $this->setAttribute('tax_ids', $tax_ids);
    }

    public function onCloning($src, $child = null)
    {
        unset($this->tax_ids);
    }
}
