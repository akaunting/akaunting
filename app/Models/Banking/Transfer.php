<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Common\Media as MediaModel;
use App\Traits\Currencies;
use App\Traits\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Traits\BelongsToThrough;

class Transfer extends Model
{
    use BelongsToThrough, Currencies, HasFactory, Media;

    protected $table = 'transfers';

    protected $appends = ['attachment'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'expense_transaction_id', 'income_transaction_id', 'created_by'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['expense.paid_at', 'expense.amount', 'expense.name', 'income.name'];

    public function expense_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'expense_transaction_id');
    }

    public function expense_account()
    {
        return $this->belongsToThrough(
            'App\Models\Banking\Account',
            'App\Models\Banking\Transaction',
            null,
            '',
            ['App\Models\Banking\Transaction' => 'expense_transaction_id']
        )->withDefault(['name' => trans('general.na')]);
    }

    public function income_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'income_transaction_id');
    }

    public function income_account()
    {
        return $this->belongsToThrough(
            'App\Models\Banking\Account',
            'App\Models\Banking\Transaction',
            null,
            '',
            ['App\Models\Banking\Transaction' => 'income_transaction_id']
        )->withDefault(['name' => trans('general.na')]);
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getAttachmentAttribute($value = null)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->all();
    }

    public function delete_attachment()
    {
        if ($attachments = $this->attachment) {
            foreach ($attachments as $file) {
                MediaModel::where('id', $file->id)->delete();
            }
        }
    }

    public function getTemplatePathAttribute($value = null)
    {
        return $value ?: 'banking.transfers.print_' . setting('transfer.template');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Transfer::new();
    }
}
