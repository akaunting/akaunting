<?php

namespace Enlightn\SecurityChecker;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use ZipArchive;

class AdvisoryFetcher
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $tempDir;

    const ADVISORIES_URL = 'https://codeload.github.com/FriendsOfPHP/security-advisories/zip/master';

    const CACHE_FILE_NAME = 'php_security_advisories.json';

    const ARCHIVE_FILE_NAME = 'php_security_advisories.zip';

    const EXTRACT_PATH = 'php_security_advisories';

    public function __construct($tempDir = null)
    {
        $this->client = new Client();
        $this->tempDir = is_null($tempDir) ? sys_get_temp_dir() : $tempDir;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchAdvisories()
    {
        $archivePath = $this->fetchAdvisoriesArchive();

        (new Filesystem)->deleteDirectory($extractPath = $this->getExtractDirectoryPath());

        $zip = new ZipArchive;
        $zip->open($archivePath);
        $zip->extractTo($extractPath);
        $zip->close();

        return $extractPath;
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchAdvisoriesArchive()
    {
        $headers = [];
        $cacheResult = [];

        if (! empty($cache = $this->getCacheFile())) {
            $cacheResult = json_decode($cache, true);

            if (is_file($cacheResult['ArchivePath'])) {
                // Set cache headers only if both the cache file and archive file exist.
                $headers = [
                    'If-None-Match' => $cacheResult['Key'],
                    'If-Modified-Since' => $cacheResult['Date'],
                ];
            }
        }

        $response = $this->client->get(self::ADVISORIES_URL, [
            'headers' => $headers,
        ]);

        if ($response->getStatusCode() !== 304) {
            $this->writeCacheFile($response);

            $this->storeAdvisoriesArchive((string) $response->getBody());

            return $this->getArchiveFilePath();
        }

        // If a 304 Not Modified header is found, simply rely on the cached archive file.
        return $cacheResult['ArchivePath'];
    }

    /**
     * @param string $content
     * @return false|int
     */
    public function storeAdvisoriesArchive($content)
    {
        return file_put_contents($this->getArchiveFilePath(), $content);
    }

    /**
     * @return false|string|null
     */
    public function getCacheFile()
    {
        if (! is_file($path = $this->getCacheFilePath())) {
            return null;
        }

        return file_get_contents($path);
    }

    public function getCacheFilePath()
    {
        return $this->tempDir.DIRECTORY_SEPARATOR.self::CACHE_FILE_NAME;
    }

    public function getArchiveFilePath()
    {
        return $this->tempDir.DIRECTORY_SEPARATOR.self::ARCHIVE_FILE_NAME;
    }

    public function getExtractDirectoryPath()
    {
        return $this->tempDir.DIRECTORY_SEPARATOR.self::EXTRACT_PATH;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    protected function writeCacheFile(ResponseInterface $response)
    {
        $cache = [
            'Key' => $response->getHeader('Etag')[0],
            'Date' => $response->getHeader('Date')[0],
            'ArchivePath' => $this->getArchiveFilePath(),
        ];

        file_put_contents($this->getCacheFilePath(), json_encode($cache), LOCK_EX);
    }
}
