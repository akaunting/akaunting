<?php

namespace Akaunting\Firewall\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    protected $table = 'firewall_logs';

    protected $fillable = ['ip', 'level', 'middleware', 'user_id', 'url', 'referrer', 'request'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(config('firewall.models.user'));
    }
}
