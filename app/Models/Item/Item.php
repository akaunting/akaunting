<?php

namespace App\Models\Item;

use App\Models\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Sofa\Eloquence\Eloquence;
use Plank\Mediable\Mediable;

class Item extends Model
{
    use Cloneable, Currencies, Eloquence, Mediable;

    protected $table = 'items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'sku', 'description', 'sale_price', 'purchase_price', 'quantity', 'category_id', 'tax_id', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    protected $sortable = ['name', 'category', 'quantity', 'sale_price', 'purchase_price', 'enabled'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'        => 10,
        'sku'         => 5,
        'description' => 2,
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category');
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax');
    }

    public function bill_items()
    {
        return $this->hasMany('App\Models\Expense\BillItem');
    }

    public function invoice_items()
    {
        return $this->hasMany('App\Models\Income\InvoiceItem');
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

    public static function getItems($filter_data = array())
    {
        if (empty($filter_data)) {
            return Item::all();
        }

        $query = Item::select('id as item_id', 'name', 'sale_price', 'purchase_price', 'tax_id');

        $query->where('quantity', '>', '0');

        foreach ($filter_data as $key => $value) {
            $query->where($key, 'LIKE', "%" . $value  . "%");
        }

        return $query->get();
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
