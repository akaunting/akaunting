<?php

namespace App\Models\Common;

use App\Traits\Media;
use App\Abstracts\Model;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\Documents;
use App\Traits\Transactions;
use App\Scopes\Contact as Scope;
use App\Models\Document\Document;
use App\Utilities\Date;
use App\Utilities\Str;
use Bkwld\Cloner\Cloneable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use Cloneable, Contacts, Currencies, Documents, HasFactory, Media, Notifiable, Transactions;

    public const CUSTOMER_TYPE = 'customer';
    public const VENDOR_TYPE = 'vendor';
    public const EMPLOYEE_TYPE = 'employee';

    protected $table = 'contacts';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['location'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'type',
        'name',
        'email',
        'user_id',
        'tax_number',
        'phone',
        'address',
        'city',
        'zip_code',
        'state',
        'country',
        'website',
        'currency_code',
        'reference',
        'enabled',
        'created_from',
        'created_by',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'enabled'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope(new Scope);
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document');
    }

    public function document_recurring()
    {
        return $this->documents()->whereIn('documents.type', $this->getRecurringDocumentTypes());
    }

    public function bills()
    {
        return $this->documents()->where('documents.type', Document::BILL_TYPE);
    }

    public function bill_recurring()
    {
        return $this->documents()->where('documents.type', Document::BILL_RECURRING_TYPE);
    }

    public function invoices()
    {
        return $this->documents()->where('documents.type', Document::INVOICE_TYPE);
    }

    public function invoice_recurring()
    {
        return $this->documents()->where('documents.type', Document::INVOICE_RECURRING_TYPE);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getIncomeTypes());
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    /**
     * Scope to only include contacts of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    /**
     * Scope to include only vendors.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVendor($query)
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getVendorTypes());
    }

    /**
     * Scope to include only customers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomer($query)
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getCustomerTypes());
    }

    public function scopeEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }

    public function onCloning($src, $child = null)
    {
        $this->email = null;
        $this->user_id = null;
    }

    public function getInitialsAttribute($value)
    {
        return Str::getInitials($this->name);
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getLogoAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('logo')) {
            return $value;
        } elseif (!$this->hasMedia('logo')) {
            return false;
        }

        return $this->getMedia('logo')->last();
    }

    public function getUnpaidAttribute()
    {
        $amount = 0;

        $collection = $this->isCustomer() ? 'invoices' : 'bills';

        $this->$collection->whereIn('status', ['sent', 'received', 'viewed', 'partial'])->each(function ($item) use (&$amount) {
            $amount += $this->convertToDefault($item->amount_due, $item->currency_code, $item->currency_rate);
        });

        return $amount;
    }

    public function getOpenAttribute()
    {
        $amount = 0;
        $today = Date::today()->toDateString();

        $collection = $this->isCustomer() ? 'invoices' : 'bills';

        $this->$collection->whereIn('status', ['sent', 'received', 'viewed', 'partial'])->where('due_at', '>=', $today)->each(function ($item) use (&$amount) {
            $amount += $this->convertToDefault($item->amount_due, $item->currency_code, $item->currency_rate);
        });

        return $amount;
    }

    public function getOverdueAttribute()
    {
        $amount = 0;
        $today = Date::today()->toDateString();

        $collection = $this->isCustomer() ? 'invoices' : 'bills';

        $this->$collection->whereIn('status', ['sent', 'received', 'viewed', 'partial'])->where('due_at', '<', $today)->each(function ($item) use (&$amount) {
            $amount += $this->convertToDefault($item->amount_due, $item->currency_code, $item->currency_rate);
        });

        return $amount;
    }

    public function getLocationAttribute()
    {
        $location = [];

        if ($this->city) {
            $location[] = $this->city;
        }

        if ($this->zip_code) {
            $location[] = $this->zip_code;
        }

        if ($this->state) {
            $location[] = $this->state;
        }

        if ($this->country && in_array($this->country, trans('countries'))) {
            $location[] = trans('countries.' . $this->country);
        }

        return implode(', ', $location);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $group = config('type.contact.' . $this->type . '.group');
        $prefix = config('type.contact.' . $this->type . '.route.prefix');
        $permission_prefix = config('type.contact.' . $this->type . '.permission.prefix');
        $translation_prefix = config('type.contact.' . $this->type . '.translation.prefix');

        if (empty($prefix)) {
            if (in_array($this->type, (array) $this->getCustomerTypes())) {
                $prefix = config('type.contact.customer.route.prefix');
            } elseif (in_array($this->type, (array) $this->getVendorTypes())) {
                $prefix = config('type.contact.vendor.route.prefix');
            } else {
                return $actions;
            }
        }

        try {
            $actions[] = [
                'title' => trans('general.show'),
                'icon' => 'visibility',
                'url' => route($prefix . '.show', $this->id),
                'permission' => 'read-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-line-actions-show-' . $this->type . '-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.edit'),
                'icon' => 'edit',
                'url' => route($prefix . '.edit', $this->id),
                'permission' => 'update-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-line-actions-edit-' . $this->type . '-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.duplicate'),
                'icon' => 'file_copy',
                'url' => route($prefix . '.duplicate', $this->id),
                'permission' => 'create-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-line-actions-duplicate-' . $this->type . '-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'type' => 'delete',
                'icon' => 'delete',
                'title' => $translation_prefix,
                'route' => $prefix . '.destroy',
                'permission' => 'delete-' . $group . '-' . $permission_prefix,
                'attributes' => [
                    'id' => 'index-line-actions-delete-' . $this->type . '-' . $this->id,
                ],
                'model' => $this,
            ];
        } catch (\Exception $e) {}

        return $actions;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Contact::new();
    }
}
