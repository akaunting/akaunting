<?php

namespace Akaunting\Firewall\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    protected $table = 'firewall_logs';

    protected $dates = ['deleted_at'];

    protected $fillable = ['ip', 'level', 'middleware', 'user_id', 'url', 'referrer', 'request'];

    public function user()
    {
        return $this->belongsTo(config('firewall.models.user'));
    }
}
