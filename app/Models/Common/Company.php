<?php

namespace App\Models\Common;

use App\Traits\Media;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Company extends Eloquent
{
    use Media, SearchString, SoftDeletes, Sortable;

    protected $table = 'companies';

    protected $dates = ['deleted_at'];

    protected $fillable = ['domain', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'domain', 'email', 'enabled', 'created_at'];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function($model) {
            $model->setSettings();
        });

        static::saving(function($model) {
            $model->unsetSettings();
        });
    }

    public function accounts()
    {
        return $this->hasMany('App\Models\Banking\Account');
    }

    public function bill_histories()
    {
        return $this->hasMany('App\Models\Expense\BillHistory');
    }

    public function bill_items()
    {
        return $this->hasMany('App\Models\Expense\BillItem');
    }

    public function bill_statuses()
    {
        return $this->hasMany('App\Models\Expense\BillStatus');
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Setting\Category');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Common\Contact');
    }

    public function currencies()
    {
        return $this->hasMany('App\Models\Setting\Currency');
    }

    public function customers()
    {
        return $this->contacts()->where('type', 'customer');
    }

    public function dashboards()
    {
        return $this->hasMany('App\Models\Common\Dashboard');
    }

    public function invoice_histories()
    {
        return $this->hasMany('App\Models\Income\InvoiceHistory');
    }

    public function invoice_items()
    {
        return $this->hasMany('App\Models\Income\InvoiceItem');
    }

    public function invoice_statuses()
    {
        return $this->hasMany('App\Models\Income\InvoiceStatus');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }

    public function payments()
    {
        return $this->transactions()->where('type', 'expense');
    }

    public function recurring()
    {
        return $this->hasMany('App\Models\Common\Recurring');
    }

    public function revenues()
    {
        return $this->transactions()->where('type', 'income');
    }

    public function settings()
    {
        return $this->hasMany('App\Models\Setting\Setting');
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Setting\Tax');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    public function transfers()
    {
        return $this->hasMany('App\Models\Banking\Transfer');
    }

    public function users()
    {
        return $this->morphedByMany('App\Models\Auth\User', 'user', 'user_companies', 'company_id', 'user_id');
    }

    public function vendors()
    {
        return $this->contacts()->where('type', 'vendor');
    }

    public function setSettings()
    {
        $settings = $this->settings;

        $groups = [
            'company',
            'default'
        ];

        foreach ($settings as $setting) {
            list($group, $key) = explode('.', $setting->getAttribute('key'));

            // Load only general settings
            if (!in_array($group, $groups)) {
                continue;
            }

            $value = $setting->getAttribute('value');

            if (($key == 'logo') && empty($value)) {
                $value = 'public/img/company.png';
            }

            $this->setAttribute($key, $value);
        }

        // Set default default company logo if empty
        if ($this->getAttribute('logo') == '') {
            $this->setAttribute('logo', 'public/img/company.png');
        }
    }

    public function unsetSettings()
    {
        $settings = $this->settings;

        $groups = [
            'company',
            'default'
        ];

        foreach ($settings as $setting) {
            list($group, $key) = explode('.', $setting->getAttribute('key'));

            // Load only general settings
            if (!in_array($group, $groups)) {
                continue;
            }

            $this->offsetUnset($key);
        }

        $this->offsetUnset('logo');
    }

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCollect($query, $sort = 'name')
    {
        $request = request();

        $search = $request->get('search');
        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }

    /**
     * Scope to only include companies of a given enabled value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query, $value = 1)
    {
        return $query->where('enabled', $value);
    }

    /**
     * Sort by company name
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function nameSortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'company.name')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }

    /**
     * Sort by company email
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function emailSortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'general.company_email')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getCompanyLogoAttribute()
    {
        return $this->getMedia('company_logo')->last();
    }
}
