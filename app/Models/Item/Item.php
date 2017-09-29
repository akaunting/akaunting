<?php

namespace App\Models\Item;

use App\Models\Model;
use App\Models\Expense\Bill;
use App\Models\Income\Invoice;
use App\Traits\Currencies;
use Sofa\Eloquence\Eloquence;

class Item extends Model
{
    use Currencies, Eloquence;

    protected $table = 'items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'sku', 'description', 'sale_price', 'purchase_price', 'quantity', 'category_id', 'tax_id', 'picture', 'enabled'];

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

    public function taxes()
    {
        return $this->hasMany('App\Models\Setting\Tax');
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice');
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getReverseConvertedAmount($format = false)
    {
        return $this->reverseConvert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    /**
     * Always return a valid picture when we retrieve it
     */
    public function getPictureAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        } else {
            return 'public/img/akaunting-logo-green.png';
        }
    }

    public function canDelete()
    {
        $error = false;

        if ($bills = $this->bills()->count()) {
            $error['bills'] = $bills;
        }

        if ($invoices = $this->invoices()->count()) {
            $error['invoices'] = $invoices;
        }

        if ($error) {
            return $error;
        }

        return true;
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
}
