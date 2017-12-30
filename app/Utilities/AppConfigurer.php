<?php
/**
 * Created by PhpStorm.
 * User: vcarmignac
 * Date: 30/12/17
 * Time: 4:29 AM
 */

namespace App\Utilities;

use Config;
use DB;
use DotenvEditor;
use App\Models\Auth\User;
use App\Models\Company\Company;
use File;
use Setting;

/**
 * Class AppConfigurer
 *
 * Contains all of the Business logic to configure the app. Either through the CLI or the `/install` web UI.
 *
 * @package App\Utilities
 */
class AppConfigurer {

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
		// App
		DotenvEditor::setKeys([
			[
				'key'       => 'APP_NAME',
				'value'     => 'Akaunting',
			],
			[
				'key'       => 'APP_ENV',
				'value'     => 'production',
			],
			[
				'key'       => 'APP_LOCALE',
				'value'     => 'en-GB',
			],
			[
				'key'       => 'APP_INSTALLED',
				'value'     => 'false',
			],
			[
				'key'       => 'APP_KEY',
				'value'     => 'base64:'.base64_encode(random_bytes(32)),
			],
			[
				'key'       => 'APP_DEBUG',
				'value'     => 'true',
			],
			[
				'key'       => 'APP_LOG_LEVEL',
				'value'     => 'debug',
			],
			[
				'key'       => 'APP_URL',
				'value'     => url('/'),
			],
		]);

		DotenvEditor::addEmpty();

		// Database
		DotenvEditor::setKeys([
			[
				'key'       => 'DB_CONNECTION',
				'value'     => 'mysql',
			],
			[
				'key'       => 'DB_HOST',
				'value'     => 'localhost',
			],
			[
				'key'       => 'DB_PORT',
				'value'     => '3306',
			],
			[
				'key'       => 'DB_DATABASE',
				'value'     => '',
			],
			[
				'key'       => 'DB_USERNAME',
				'value'     => '',
			],
			[
				'key'       => 'DB_PASSWORD',
				'value'     => '',
			],
			[
				'key'       => 'DB_PREFIX',
				'value'     => '',
			],
		]);

		DotenvEditor::addEmpty();

		// Drivers
		DotenvEditor::setKeys([
			[
				'key'       => 'BROADCAST_DRIVER',
				'value'     => 'log',
			],
			[
				'key'       => 'CACHE_DRIVER',
				'value'     => 'file',
			],
			[
				'key'       => 'SESSION_DRIVER',
				'value'     => 'file',
			],
			[
				'key'       => 'QUEUE_DRIVER',
				'value'     => 'database',
			],
		]);

		DotenvEditor::addEmpty();

		// Mail
		DotenvEditor::setKeys([
			[
				'key'       => 'MAIL_DRIVER',
				'value'     => 'mail',
			],
			[
				'key'       => 'MAIL_HOST',
				'value'     => 'localhost',
			],
			[
				'key'       => 'MAIL_PORT',
				'value'     => '2525',
			],
			[
				'key'       => 'MAIL_USERNAME',
				'value'     => 'null',
			],
			[
				'key'       => 'MAIL_PASSWORD',
				'value'     => 'null',
			],
			[
				'key'       => 'MAIL_ENCRYPTION',
				'value'     => 'null',
			],
		]);

		DotenvEditor::save();
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
	public static function isDbValid($host, $port, $database, $username, $password){
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
		} catch (\Exception $e) {;
			return false;
		}

		// Purge test connection
		DB::purge('install_test');

		return true;
	}

	public static function saveDbVariables($host, $port, $database, $username, $password)
	{
		$prefix = strtolower(str_random(3) . '_');

		// Save to file
		DotenvEditor::setKeys([
			[
				'key'       => 'DB_HOST',
				'value'     => $host,
			],
			[
				'key'       => 'DB_PORT',
				'value'     => $port,
			],
			[
				'key'       => 'DB_DATABASE',
				'value'     => $database,
			],
			[
				'key'       => 'DB_USERNAME',
				'value'     => $username,
			],
			[
				'key'       => 'DB_PASSWORD',
				'value'     => $password,
			],
			[
				'key'       => 'DB_PREFIX',
				'value'     => $prefix,
			],
		])->save();

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

	public static function createCompany($companyName, $companyEmail, $locale)
	{
		// Create company
		$company = Company::create([
			'domain' => '',
		]);

		// Set settings
		Setting::set([
			'general.company_name'          => $companyName,
			'general.company_email'         => $companyEmail,
			'general.default_currency'      => 'USD',
			'general.default_locale'        => $locale,
		]);
		Setting::setExtraColumns(['company_id' => $company->id]);
		Setting::save();
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
		DotenvEditor::setKeys([
			[
				'key'       => 'APP_LOCALE',
				'value'     => session('locale'),
			],
			[
				'key'       => 'APP_INSTALLED',
				'value'     => 'true',
			],
			[
				'key'       => 'APP_DEBUG',
				'value'     => 'false',
			],
		])->save();

		// Rename the robots.txt file
		try {
			File::move(base_path('robots.txt.dist'), base_path('robots.txt'));
		} catch (\Exception $e) {
			// nothing to do
		}
	}
}