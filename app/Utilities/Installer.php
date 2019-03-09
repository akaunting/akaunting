<?php

namespace App\Utilities;

use App\Models\Auth\User;
use App\Models\Common\Company;
use Artisan;
use Config;
use DB;
use File;

/**
 * Class Installer
 *
 * Contains all of the Business logic to install the app. Either through the CLI or the `/install` web UI.
 *
 * @package App\Utilities
 */
class Installer
{

    public static function checkServerRequirements()
    {
        $requirements = array();

        if (ini_get('safe_mode')) {
            $requirements[] = trans('install.requirements.disabled', ['feature' => 'Safe Mode']);
        }

        if (ini_get('register_globals')) {
            $requirements[] = trans('install.requirements.disabled', ['feature' => 'Register Globals']);
        }

        if (ini_get('magic_quotes_gpc')) {
            $requirements[] = trans('install.requirements.disabled', ['feature' => 'Magic Quotes']);
        }

        if (!ini_get('file_uploads')) {
            $requirements[] = trans('install.requirements.enabled', ['feature' => 'File Uploads']);
        }

        if (!function_exists('proc_open')) {
            $requirements[] = trans('install.requirements.enabled', ['feature' => 'proc_open']);
        }

        if (!function_exists('proc_close')) {
            $requirements[] = trans('install.requirements.enabled', ['feature' => 'proc_close']);
        }

        if (!class_exists('PDO')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'MySQL PDO']);
        }

        if (!extension_loaded('openssl')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'OpenSSL']);
        }

        if (!extension_loaded('tokenizer')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'Tokenizer']);
        }

        if (!extension_loaded('mbstring')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'mbstring']);
        }

        if (!extension_loaded('curl')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'cURL']);
        }

        if (!extension_loaded('xml')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'XML']);
        }

        if (!extension_loaded('zip')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'ZIP']);
        }

        if (!extension_loaded('fileinfo')) {
            $requirements[] = trans('install.requirements.extension', ['extension' => 'FileInfo']);
        }

        if (!is_writable(base_path('storage/app'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/app']);
        }

        if (!is_writable(base_path('storage/app/uploads'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/app/uploads']);
        }

        if (!is_writable(base_path('storage/framework'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/framework']);
        }

        if (!is_writable(base_path('storage/logs'))) {
            $requirements[] = trans('install.requirements.directory', ['directory' => 'storage/logs']);
        }

        return $requirements;
    }

    /**
     * Create a default .env file.
     *
     * @return void
     */
	public static function createDefaultEnvFile()
	{
        // Rename file
        if (is_file(base_path('.env.example'))) {
            File::move(base_path('.env.example'), base_path('.env'));
        }

        // Update .env file
        static::updateEnv([
            'APP_KEY'   =>  'base64:'.base64_encode(random_bytes(32)),
        ]);
	}

    public static function createDbTables($host, $port, $database, $username, $password)
    {
        if (!static::isDbValid($host, $port, $database, $username, $password)) {
            return false;
        }

        // Set database details
        static::saveDbVariables($host, $port, $database, $username, $password);

        // Try to increase the maximum execution time
        set_time_limit(300); // 5 minutes

        // Create tables
        Artisan::call('migrate', ['--force' => true]);

        // Create Roles
        Artisan::call('db:seed', ['--class' => 'Database\Seeds\Roles', '--force' => true]);

        return true;
    }

    /**
     * Check if the database exists and is accessible.
     *
     * @param $host
     * @param $port
     * @param $database
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public static function isDbValid($host, $port, $database, $username, $password)
    {
        Config::set('database.connections.install_test', [
            'host'      => $host,
            'port'      => $port,
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'driver'    => env('DB_CONNECTION', 'mysql'),
            'charset'   => env('DB_CHARSET', 'utf8mb4'),
        ]);

        try {
            DB::connection('install_test')->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        // Purge test connection
        DB::purge('install_test');

        return true;
    }

    public static function saveDbVariables($host, $port, $database, $username, $password)
    {
        $prefix = strtolower(str_random(3) . '_');

        // Update .env file
        static::updateEnv([
            'DB_HOST'       =>  $host,
            'DB_PORT'       =>  $port,
            'DB_DATABASE'   =>  $database,
            'DB_USERNAME'   =>  $username,
            'DB_PASSWORD'   =>  $password,
            'DB_PREFIX'     =>  $prefix,
        ]);

        $con = env('DB_CONNECTION', 'mysql');

        // Change current connection
        $db = Config::get('database.connections.' . $con);

        $db['host'] = $host;
        $db['database'] = $database;
        $db['username'] = $username;
        $db['password'] = $password;
        $db['prefix'] = $prefix;

        Config::set('database.connections.' . $con, $db);

        DB::purge($con);
        DB::reconnect($con);
    }

    public static function createCompany($name, $email, $locale)
    {
        // Create company
        $company = Company::create([
            'domain' => '',
        ]);

        // Set settings
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->set([
            'general.company_name'          => $name,
            'general.company_email'         => $email,
            'general.default_currency'      => 'USD',
            'general.default_locale'        => $locale,
        ]);
        setting()->save();
    }

    public static function createUser($email, $password, $locale)
    {
        // Create the user
        $user = User::create([
            'name' => '',
            'email' => $email,
            'password' => $password,
            'locale' => $locale,
        ]);

        // Attach admin role
        $user->roles()->attach('1');

        // Attach company
        $user->companies()->attach('1');
    }

    public static function finalTouches()
    {
        // Update .env file
        static::updateEnv([
            'APP_LOCALE'    =>  session('locale'),
            'APP_INSTALLED' =>  'true',
            'APP_DEBUG'     =>  'false',
        ]);

        // Rename the robots.txt file
        try {
            File::move(base_path('robots.txt.dist'), base_path('robots.txt'));
        } catch (\Exception $e) {
            // nothing to do
        }
    }

    public static function updateEnv($data)
    {
        if (empty($data) || !is_array($data) || !is_file(base_path('.env'))) {
            return false;
        }

        $env = file_get_contents(base_path('.env'));

        $env = explode("\n", $env);

        foreach ($data as $data_key => $data_value) {
            $updated = false;

            foreach ($env as $env_key => $env_value) {
                $entry = explode('=', $env_value, 2);

                // Check if new or old key
                if ($entry[0] == $data_key) {
                    $env[$env_key] = $data_key . '=' . $data_value;
                    $updated = true;
                } else {
                    $env[$env_key] = $env_value;
                }
            }

            // Lets create if not available
            if (!$updated) {
                $env[] = $data_key . '=' . $data_value;
            }
        }

        $env = implode("\n", $env);

        file_put_contents(base_path('.env'), $env);

        return true;
    }
}
