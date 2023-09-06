<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\VersionUpdater\Downloader;

use Psy\Exception\ErrorException;
use Psy\Shell;
use Psy\VersionUpdater\Downloader;

class CurlDownloader implements Downloader
{
    private $tempDir = null;
    private $outputFile = null;

    /** {@inheritDoc} */
    public function setTempDir(string $tempDir)
    {
        $this->tempDir = $tempDir;
    }

    /** {@inheritDoc} */
    public function download(string $url): bool
    {
        $tempDir = $this->tempDir ?: \sys_get_temp_dir();
        $this->outputFile = \tempnam($tempDir, 'psysh-archive-');
        $targetName = $this->outputFile.'.tar.gz';

        if (!\rename($this->outputFile, $targetName)) {
            return false;
        }

        $this->outputFile = $targetName;

        $outputHandle = \fopen($this->outputFile, 'w');
        if (!$outputHandle) {
            return false;
        }
        $curl = \curl_init();
        \curl_setopt_array($curl, [
            \CURLOPT_FAILONERROR    => true,
            \CURLOPT_HEADER         => 0,
            \CURLOPT_FOLLOWLOCATION => true,
            \CURLOPT_TIMEOUT        => 10,
            \CURLOPT_FILE           => $outputHandle,
            \CURLOPT_HTTPHEADER     => [
                'User-Agent' => 'PsySH/'.Shell::VERSION,
            ],
        ]);
        \curl_setopt($curl, \CURLOPT_URL, $url);
        $result = \curl_exec($curl);
        $error = \curl_error($curl);
        \curl_close($curl);

        \fclose($outputHandle);

        if (!$result) {
            throw new ErrorException('cURL Error: '.$error);
        }

        return (bool) $result;
    }

    /** {@inheritDoc} */
    public function getFilename(): string
    {
        return $this->outputFile;
    }

    /** {@inheritDoc} */
    public function cleanup()
    {
        if (\file_exists($this->outputFile)) {
            \unlink($this->outputFile);
        }
    }
}
