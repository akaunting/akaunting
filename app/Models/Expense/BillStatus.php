<?php

namespace App\Models\Expense;

use App\Models\Model;

class BillStatus extends Model
{

    protected $table = 'bill_statuses';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['label'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'code'];

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getLabelAttribute()
    {
        switch ($this->code) {
            case 'paid':
                $label = 'label-success';
                break;
            case 'delete':
                $label = 'label-danger';
                break;
            case 'partial':
            case 'received':
                $label = 'label-warning';
                break;
            default:
                $label = 'bg-aqua';
                break;
        }

        return $label;
    }
}
