<?php

namespace Akaunting\Firewall\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ip extends Model
{
    use SoftDeletes;

    protected $table = 'firewall_ips';

    protected $fillable = ['ip', 'log_id', 'blocked'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function log()
    {
        return $this->belongsTo('Akaunting\Firewall\Models\Log');
    }

    public function logs()
    {
        return $this->hasMany('Akaunting\Firewall\Models\Log', 'ip', 'ip');
    }

    public function scopeBlocked($query, $ip = null)
    {
        $q = $query->where('blocked', 1);

        if ($ip) {
            $q = $query->where('ip', $ip);
        }

        return $q;
    }
}
