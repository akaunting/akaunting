<?php

namespace App\Models\Auth;

use App\Traits\Tenants;
use App\Notifications\Auth\Reset;
use App\Traits\Media;
use Date;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Kyslik\ColumnSortable\Sortable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class User extends Authenticatable
{
    use LaratrustUserTrait, Notifiable, SearchString, SoftDeletes, Sortable, Media, Tenants;

    protected $table = 'users';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'locale', 'enabled', 'landing_page'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_logged_in_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'enabled'];

    public function companies()
    {
        return $this->morphToMany('App\Models\Common\Company', 'user', 'user_companies', 'user_id', 'company_id');
    }

    public function contact()
    {
        return $this->hasOne('App\Models\Common\Contact', 'user_id', 'id');
    }

    public function dashboards()
    {
        return $this->morphToMany('App\Models\Common\Dashboard', 'user', 'user_dashboards', 'user_id', 'dashboard_id');
    }

    /**
     * Always capitalize the name when we retrieve it
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Always return a valid picture when we retrieve it
     */
    public function getPictureAttribute($value)
    {
        // Check if we should use gravatar
        if (setting('default.use_gravatar', '0') == '1') {
            try {
                // Check for gravatar
                $url = 'https://www.gravatar.com/avatar/' . md5(strtolower($this->getAttribute('email'))).'?size=90&d=404';

                $client = new \GuzzleHttp\Client(['verify' => false]);

                $client->request('GET', $url)->getBody()->getContents();

                $value = $url;
            } catch (RequestException $e) {
                // 404 Not Found
            }
        }

        if (!empty($value) && !$this->hasMedia('picture')) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }

    /**
     * Always return a valid picture when we retrieve it
     */
    public function getLastLoggedInAtAttribute($value)
    {
        // Date::setLocale('tr');

        if (!empty($value)) {
            return Date::parse($value)->diffForHumans();
        } else {
            return trans('auth.never');
        }
    }

    /**
     * Send reset link to user via email
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Reset($token));
    }

    /**
     * Always capitalize the name when we save it to the database
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    /**
     * Always hash the password when we save it to the database
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
     * Scope to only include active currencies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }
}
