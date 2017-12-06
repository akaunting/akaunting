<?php

namespace App\Traits;

use App\Utilities\Info;
use Artisan;
use File;
use GuzzleHttp\Client;
use Module;
use ZipArchive;

trait Modules
{

    public function getModules()
    {
        $response = $this->getRemote('apps/items');

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getModule($alias)
    {
        $response = $this->getRemote('apps/' . $alias);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getCategories()
    {
        $response = $this->getRemote('apps/categories');

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getModulesByCategory($alias)
    {
        $response = $this->getRemote('apps/categories/' . $alias);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getPaidModules($data = [])
    {
        $response = $this->getRemote('apps/paid', 'GET', $data);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getNewModules($data = [])
    {
        $response = $this->getRemote('apps/new', 'GET', $data);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getFreeModules($data = [])
    {
        $response = $this->getRemote('apps/free', 'GET', $data);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getCoreVersion()
    {
        $data['query'] = Info::all();

        $response = $this->getRemote('core/version', 'GET', $data);

        if ($response->getStatusCode() == 200) {
            return $response->json();
        }

        return [];
    }

    public function downloadModule($path)
    {
        $response = $this->getRemote($path);

        if ($response->getStatusCode() == 200) {
            $file = $response->getBody()->getContents();

            $path = 'temp-' . md5(mt_rand());
            $temp_path = storage_path('app/temp') . '/' . $path;

            $file_path = $temp_path . '/upload.zip';

            // Create tmp directory
            if (!File::isDirectory($temp_path)) {
                File::makeDirectory($temp_path);
            }

            // Add content to the Zip file
            $uploaded = is_int(file_put_contents($file_path, $file)) ? true : false;

            if (!$uploaded) {
                return false;
            }

            $data = [
                'path' => $path
            ];

            return [
                'success' => true,
                'errors' => false,
                'data' => $data,
            ];
        }

        return [
            'success' => false,
            'errors' => true,
            'data' => null,
        ];
    }

    public function unzipModule($path)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (!$zip->open($file) || !$zip->extractTo($temp_path)) {
            return [
                'success' => false,
                'errors' => true,
                'data' => null,
            ];
        }

        $zip->close();

        // Remove Zip
        File::delete($file);

        $data = [
            'path' => $path
        ];

        return [
            'success' => true,
            'errors' => false,
            'data' => $data,
        ];
    }

    public function installModule($path)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $modules_path = base_path() . '/modules';

        // Create modules directory
        if (!File::isDirectory($modules_path)) {
            File::makeDirectory($modules_path);
        }

        $module = json_decode(file_get_contents($temp_path . '/module.json'));

        $module_path = $modules_path . '/' . $module->name;

        // Create module directory
        if (!File::isDirectory($module_path)) {
            File::makeDirectory($module_path);
        }

        // Move all files/folders from temp path then delete it
        File::copyDirectory($temp_path, $module_path);
        File::deleteDirectory($temp_path);

        Artisan::call('cache:clear');

        $data = [
            'path'  => $path,
            'name' => $module->name,
            'alias' => $module->alias
        ];

        return [
            'success' => true,
            'installed' => true,
            'errors' => false,
            'data' => $data,
        ];
    }

    public function uninstallModule($alias)
    {
        $module = Module::findByAlias($alias);

        $data = [
            'name' => $module->get('name'),
            'category' => $module->get('category'),
            'version' => $module->get('version'),
        ];

        $module->delete();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data'   => $data
        ];
    }

    public function enableModule($alias)
    {
        $module = Module::findByAlias($alias);

        $data = [
            'name' => $module->get('name'),
            'category' => $module->get('category'),
            'version' => $module->get('version'),
        ];

        $module->enable();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data'   => $data
        ];
    }

    public function disableModule($alias)
    {
        $module = Module::findByAlias($alias);

        $data = [
          'name' => $module->get('name'),
          'category' => $module->get('category'),
          'version' => $module->get('version'),
        ];

        $module->disable();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data'   => $data
        ];
    }

    protected function getRemote($path, $method = 'GET', $data = array())
    {
        $base = 'https://akaunting.com/api/';

        $client = new Client(['verify' => false, 'base_uri' => $base]);

        $headers['headers'] = [
            'Authorization' => 'Bearer ' . setting('general.api_token'),
            'Accept'        => 'application/json',
            'Referer'       => env('APP_URL'),
        ];

        $data['http_errors'] = false;

        $data = array_merge($data, $headers);

        $result = $client->request($method, $path, $data);

        return $result;
    }
}
