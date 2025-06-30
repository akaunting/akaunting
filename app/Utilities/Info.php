<?php

namespace App\Utilities;

use Akaunting\Version\Version;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Traits\Settings;
use Composer\InstalledVersions;
use Illuminate\Support\Facades\DB;

class Info
{
    public static function all()
    {
        static $info = [];

        $basic = [
            'api_key' => static::getApiKey(),
            'ip' => static::ip(),
        ];

        if (! empty($info) || is_cloud()) {
            if (is_cloud()) {
                $basic['companies'] = 0;
            }

            return array_merge(static::versions(), $info, $basic);
        }

        $users_count = user_model_class()::query()->isNotCustomer()->count();

        $info = array_merge(static::versions(), $basic, [
            'companies' => Company::count(),
            'users' => $users_count,
            'invoices' => Document::allCompanies()->invoice()->count(),
            'customers' => Contact::allCompanies()->customer()->count(),
            'php_extensions' => static::phpExtensions(),
        ]);

        return $info;
    }

    public static function versions()
    {
        static $versions = [];

        if (! empty($versions)) {
            return $versions;
        }

        $versions = [
            'akaunting' => Version::short(),
            'laravel' => InstalledVersions::getPrettyVersion('laravel/framework'),
            'php' => static::phpVersion(),
            'mysql' => static::mysqlVersion(),
            'guzzle' => InstalledVersions::getPrettyVersion('guzzlehttp/guzzle'),
            'livewire' => InstalledVersions::getPrettyVersion('livewire/livewire'),
            'omnipay' => InstalledVersions::getPrettyVersion('league/omnipay'),
        ];

        return $versions;
    }

    public static function phpVersion()
    {
        return phpversion();
    }

    public static function phpExtensions()
    {
        return get_loaded_extensions();
    }

    public static function mysqlVersion()
    {
        static $version;

        if (empty($version) && (config('database.default') === 'mysql')) {
            $version = DB::selectOne('select version() as mversion')->mversion;
        }

        if (isset($version)) {
            return $version;
        }

        return 'N/A';
    }

    public static function ip()
    {
        return request()->header('CF_CONNECTING_IP')
                ? request()->header('CF_CONNECTING_IP')
                : request()->ip();
    }

    public static function getApiKey(): string
    {
        $setting = new class() { use Settings; };

        return $setting->getSettingValue('apps.api_key', '');
    }
}
