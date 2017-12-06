<?php

namespace App\Http\Controllers\Install;

use DotenvEditor;
use File;
use Illuminate\Routing\Controller;

class Requirements extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function show()
    {
        // Check requirements
        $requirements = $this->check();

        if (empty($requirements)) {
            // Create the .env file
            if (!File::exists(base_path('.env'))) {
                $this->createEnvFile();
            }

            redirect('install/language')->send();
        } else {
            foreach ($requirements as $requirement) {
                flash($requirement)->error()->important();
            }

            return view('install.requirements.show');
        }
    }

    /**
     * Check the requirements.
     *
     * @return array
     */
    private function check()
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
     * Create the .env file.
     *
     * @return void
     */
    private function createEnvFile()
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
}
