<?php

namespace App\Utilities;

use App\Models\Auth\User;
use App\Models\Common\Company;
use DB;

class Info
{
    public static function all()
    {
        return array_merge(static::versions(), [
            'api_key' => setting('apps.api_key'),
            'companies' => Company::count(),
            'users' => User::count(),
        ]);
    }

    public static function versions()
    {
        return [
            'akaunting' => version('short'),
            'php' => static::phpVersion(),
            'mysql' => static::mysqlVersion(),
        ];
    }

    public static function phpVersion()
    {
        return phpversion();
    }

    public static function mysqlVersion()
    {
        if (env('DB_CONNECTION') === 'mysql') {
            return DB::selectOne('select version() as mversion')->mversion;
        }

        return 'N/A';
    }
}
