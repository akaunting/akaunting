<?php

namespace App\Utilities;

use DB;
use App\Models\Common\Company;

class Info
{

    public static function versions()
    {
        $v = array();

        $v['akaunting'] = version('short');

        $v['php'] = static::phpVersion();

        $v['mysql'] = static::mysqlVersion();

        return $v;
    }

    public static function all()
    {
        $data = static::versions();

        $data['token'] = setting('general.api_token');

        $data['companies'] = Company::all()->count();

        return $data;
    }

    public static function phpVersion()
    {
        return phpversion();
    }
  
    public static function mysqlVersion()
    {
        if(env('DB_CONNECTION') === 'mysql')
        {
            return DB::selectOne('select version() as mversion')->mversion;
        }

        return "N/A";
    }
}