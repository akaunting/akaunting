<?php

namespace App\Models\Setting;

use App\Abstracts\Model;
use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxes';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'rate', 'type', 'enabled', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate'          => 'double',
        'enabled'       => 'boolean',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'rate', 'enabled'];

    public function items()
    {
        return $this->hasMany('App\Models\Common\ItemTax');
    }

    public function document_items()
    {
        return $this->hasMany('App\Models\Document\DocumentItemTax');
    }

    public function bill_items()
    {
        return $this->document_items()->where('document_item_taxes.type', Document::BILL_TYPE);
    }

    public function invoice_items()
    {
        return $this->document_items()->where('document_item_taxes.type', Document::INVOICE_TYPE);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    public function scopeRate($query, $rate)
    {
        return $query->where('rate', '=', $rate);
    }

    public function scopeNotRate($query, $rate)
    {
        return $query->where('rate', '<>', $rate);
    }

    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    public function scopeFixed($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'fixed');
    }

    public function scopeNormal($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'normal');
    }

    public function scopeInclusive($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'inclusive');
    }

    public function scopeCompound($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'compound');
    }

    public function scopeWithholding($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'withholding');
    }

    public function scopeNotWithholding($query)
    {
        return $query->where($this->qualifyColumn('type'), '<>', 'withholding');
    }

    /**
     * Get the name including rate.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        $title = $this->name . ' (';

        if (setting('localisation.percent_position', 'after') == 'after') {
            $title .= $this->getAttribute('type') == 'fixed' ?  $this->rate : $this->rate . '%';
        } else {
            $title .= $this->getAttribute('type') == 'fixed' ?  $this->rate : '%' . $this->rate;
        }
        $title .= ')';

        return $title;
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('taxes.edit', $this->id),
            'permission' => 'update-settings-taxes',
            'attributes' => [
                'id' => 'index-line-actions-edit-tax-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'taxes.destroy',
            'permission' => 'delete-settings-taxes',
            'attributes' => [
                'id' => 'index-line-actions-delete-tax-' . $this->id,
            ],
            'model' => $this,
        ];

        return $actions;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Tax::new();
    }
}
