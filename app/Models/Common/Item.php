<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Traits\Currencies;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;

class Item extends Model
{
    use Cloneable, Currencies, Media;

    protected $table = 'items';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['item_id'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'description', 'sale_price', 'purchase_price', 'category_id', 'tax_id', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    protected $sortable = ['name', 'category', 'sale_price', 'purchase_price', 'enabled'];

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax')->withDefault(['name' => trans('general.na'), 'rate' => 0]);
    }

    public function bill_items()
    {
        return $this->hasMany('App\Models\Purchase\BillItem');
    }

    public function invoice_items()
    {
        return $this->hasMany('App\Models\Sale\InvoiceItem');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    /**
     * Convert sale price to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setSalePriceAttribute($value)
    {
        $this->attributes['sale_price'] = (double) $value;
    }

    /**
     * Convert purchase price to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setPurchasePriceAttribute($value)
    {
        $this->attributes['purchase_price'] = (double) $value;
    }

    /**
     * Get the item id.
     *
     * @return string
     */
    public function getItemIdAttribute()
    {
        return $this->id;
    }

    /**
     * Scope autocomplete.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAutocomplete($query, $filter)
    {
        return $query->where(function ($query) use ($filter) {
            foreach ($filter as $key => $value) {
                $query->orWhere($key, 'LIKE', "%" . $value  . "%");
            }
        });
    }

    /**
     * Sort by category name
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function categorySortable($query, $direction)
    {
        return $query->join('categories', 'categories.id', '=', 'items.category_id')
            ->orderBy('name', $direction)
            ->select('items.*');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getPictureAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('picture')) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }
}
