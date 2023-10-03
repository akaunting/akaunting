<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Traits\Contacts;
use App\Utilities\Str;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class ContactPerson extends Model
{
    use Cloneable, Contacts, Notifiable;

    protected $table = 'contact_persons';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['initials'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'type', 'contact_id', 'name', 'email', 'phone', 'created_from', 'created_by'];

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na'), 'rate' => 0]);
    }

    public function scopeType(Builder $query, array $types): Builder
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    public function scopeVendor(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getVendorTypes());
    }

    public function scopeCustomer(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getCustomerTypes());
    }

    public function scopeEmployee(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getEmployeeTypes());
    }

    public function scopeEmail(Builder $query, $email): Builder
    {
        return $query->where('email', '=', $email);
    }

    public function getInitialsAttribute($value)
    {
        return Str::getInitials($this->name);
    }
}
