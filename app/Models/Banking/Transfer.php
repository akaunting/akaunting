<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Common\Media as MediaModel;
use App\Traits\Currencies;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Traits\BelongsToThrough;

class Transfer extends Model
{
    use BelongsToThrough, Cloneable, Currencies, HasFactory, Media;

    protected $table = 'transfers';

    protected $appends = [
        'attachment',
        'from_account_id',
        'from_currency_code',
        'from_account_rate',
        'to_account_id',
        'to_currency_code',
        'to_account_rate',
        'transferred_at',
        'description',
        'amount',
        'payment_method',
        'reference',
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'expense_transaction_id', 'income_transaction_id', 'created_from', 'created_by'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['expense.paid_at', 'expense.amount', 'expense.name', 'income.name'];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['expense_transaction', 'income_transaction'];

    public function expense_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'expense_transaction_id')
                        ->withoutGlobalScope('App\Scopes\Transaction')
                        ->withDefault(['name' => trans('general.na')]);
    }

    public function expense_account()
    {
        return $this->belongsToThrough(
                            'App\Models\Banking\Account',
                            'App\Models\Banking\Transaction',
                            null,
                            '',
                            ['App\Models\Banking\Transaction' => 'expense_transaction_id']
                        )
                        ->withoutGlobalScope('App\Scopes\Transaction')
                        ->withDefault(['name' => trans('general.na')]);
    }

    public function income_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'income_transaction_id')
                        ->withoutGlobalScope('App\Scopes\Transaction')
                        ->withDefault(['name' => trans('general.na')]);
    }

    public function income_account()
    {
        return $this->belongsToThrough(
                            'App\Models\Banking\Account',
                            'App\Models\Banking\Transaction',
                            null,
                            '',
                            ['App\Models\Banking\Transaction' => 'income_transaction_id']
                        )
                        ->withoutGlobalScope('App\Scopes\Transaction')
                        ->withDefault(['name' => trans('general.na')]);
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
     * Get the current balance.
     *
     * @return int
     */
    public function getFromAccountIdAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->account_id;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getFromCurrencyCodeAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->currency_code;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getFromAccountRateAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->currency_rate;
    }

    /**
     * Get the current balance.
     *
     * @return int
     */
    public function getToAccountIdAttribute($value = null)
    {
        return $value ?: $this->income_transaction->account_id;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getToCurrencyCodeAttribute($value = null)
    {
        return $value ?: $this->income_transaction->currency_code;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getToAccountRateAttribute($value = null)
    {
        return $value ?: $this->income_transaction->currency_rate;
    }

    /**
     * Get the current balance.
     *
     * @return date
     */
    public function getTransferredAtAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->paid_at;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getDescriptionAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->description;
    }

    /**
     * Get the current balance.
     *
     * @return float
     */
    public function getAmountAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->amount;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getPaymentMethodAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->payment_method;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getReferenceAttribute($value = null)
    {
        return $value ?: $this->expense_transaction->reference;
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
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('transfers.show', $this->id),
            'permission' => 'read-banking-transfers',
            'attributes' => [
                'id' => 'index-line-actions-show-transfer-' . $this->id,
            ],
        ];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('transfers.edit', $this->id),
            'permission' => 'update-banking-transfers',
            'attributes' => [
                'id' => 'index-line-actions-edit-transfer-' . $this->id,
            ],
        ];

        $actions[] = [
            'title' => trans('general.duplicate'),
            'icon' => 'file_copy',
            'url' => route('transfers.duplicate', $this->id),
            'permission' => 'update-banking-transfers',
            'attributes' => [
                'id' => 'index-line-actions-duplicate-transfer-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'transfers.destroy',
            'permission' => 'delete-banking-transfers',
            'attributes' => [
                'id' => 'index-line-actions-delete-transfer-' . $this->id,
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
        return \Database\Factories\Transfer::new();
    }
}
