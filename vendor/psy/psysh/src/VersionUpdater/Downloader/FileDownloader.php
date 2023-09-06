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

use Psy\VersionUpdater\Downloader;

class FileDownloader implements Downloader
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

        return (bool) \file_put_contents($this->outputFile, \file_get_contents($url));
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
