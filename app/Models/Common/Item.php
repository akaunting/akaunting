<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Models\Document\Document;
use App\Traits\Currencies;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use Cloneable, Currencies, HasFactory, Media;

    protected $table = 'items';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['item_id', 'tax_ids'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'description', 'sale_price', 'purchase_price', 'category_id', 'enabled', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale_price' => 'double',
        'purchase_price' => 'double',
        'enabled' => 'boolean',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    protected $sortable = ['name', 'category', 'sale_price', 'purchase_price', 'enabled'];

    /**
     * @var array
     */
    public $cloneable_relations = ['taxes'];

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Common\ItemTax');
    }

    public function document_items()
    {
        return $this->hasMany('App\Models\Document\DocumentItem');
    }

    public function bill_items()
    {
        return $this->document_items()->where('type', Document::BILL_TYPE);
    }

    public function invoice_items()
    {
        return $this->document_items()->where('type', Document::INVOICE_TYPE);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
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
     * Get the item id.
     *
     * @return string
     */
    public function getTaxIdsAttribute()
    {
        return $this->taxes()->pluck('tax_id');
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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Item::new();
    }
}
