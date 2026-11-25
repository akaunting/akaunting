<?php

namespace App\Models\Auth;

use App\Abstracts\Model;

class UserInvitation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_invitations';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['user_id', 'token', 'created_from', 'created_by'];

    public function user()
    {
        return $this->belongsTo(user_model_class());
    }
 
    /**
     * Scope a query to only include given token value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeToken($query, $token)
    {
        $query->where('token', $token);
    }

    /**
     * Scope a query to only include the invitations sent within the days set in
     * app.invitations.expire, all of them when it is empty or 0.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeNotExpired($query)
    {
        $days = (int) config('app.invitations.expire');

        if ($days <= 0) {
            return;
        }

        $query->where($this->qualifyColumn('created_at'), '>=', now()->subDays($days));
    }
}
