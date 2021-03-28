<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use App\Traits\SiteApi;
use App\Utilities\Info;
use Illuminate\Support\Facades\File;

class DownloadFile extends Job
{
    use SiteApi;

    protected $alias;

    protected $version;

    /**
     * Create a new job instance.
     *
     * @param  $alias
     * @param  $version
     */
    public function __construct($alias, $version)
    {
        $this->alias = $alias;
        $this->version = $version;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        if (!$response = static::getResponse('GET', $this->getUrl(), ['timeout' => 50, 'track_redirects' => true])) {
            throw new \Exception(trans('modules.errors.download', ['module' => $this->alias]));
        }

        $file = $response->getBody()->getContents();

        $path = 'temp-' . md5(mt_rand());
        $temp_path = storage_path('app/temp/' . $path);

        $file_path = $temp_path . '/upload.zip';

        // Create tmp directory
        if (!File::isDirectory($temp_path)) {
            File::makeDirectory($temp_path);
        }

        // Add content to the Zip file
        $uploaded = is_int(file_put_contents($file_path, $file)) ? true : false;

        if (!$uploaded) {
            throw new \Exception(trans('modules.errors.download', ['module' => $this->alias]));
        }

        return $path;
    }

    protected function getUrl()
    {
        if ($this->alias == 'core') {
            $info = Info::all();

            $url = 'core/download/' . $this->version . '/' . $info['php'] . '/' . $info['mysql'];
        } else {
            $url = 'apps/' . $this->alias . '/download/' . $this->version . '/' . version('short') . '/' . setting('apps.api_key');
        }

        return $url;
    }
}
