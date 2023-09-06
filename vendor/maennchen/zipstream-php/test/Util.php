<?php

declare(strict_types=1);

namespace ZipStream\Test;

use function fgets;
use function pclose;
use function popen;
use function preg_match;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function strtolower;

use ZipArchive;

trait Util
{
    protected function getTmpFileStream(): array
    {
        $tmp = tempnam(sys_get_temp_dir(), 'zipstreamtest');
        $stream = fopen($tmp, 'wb+');

        return [$tmp, $stream];
    }

    protected function cmdExists(string $command): bool
    {
        if (strtolower(\substr(PHP_OS, 0, 3)) === 'win') {
            $fp = popen("where $command", 'r');
            $result = fgets($fp, 255);
            $exists = !preg_match('#Could not find files#', $result);
            pclose($fp);
        } else { // non-Windows
            $fp = popen("which $command", 'r');
            $result = fgets($fp, 255);
            $exists = !empty($result);
            pclose($fp);
        }

        return $exists;
    }

    protected function dumpZipContents(string $path): string
    {
        if (!$this->cmdExists('hexdump')) {
            return '';
        }

        $output = [];

        if (!exec("hexdump -C \"$path\" | head -n 50", $output)) {
            return '';
        }

        return "\nHexdump:\n" . implode("\n", $output);
    }

    protected function validateAndExtractZip(string $zipPath): string
    {
        $tmpDir = $this->getTmpDir();

        $zipArchive = new ZipArchive();
        $result = $zipArchive->open($zipPath);

        if ($result !== true) {
            $codeName = $this->zipArchiveOpenErrorCodeName($result);
            $debugInformation = $this->dumpZipContents($zipPath);

            $this->fail("Failed to open {$zipPath}. Code: $result ($codeName)$debugInformation");

            return $tmpDir;
        }

        $this->assertSame(0, $zipArchive->status);
        $this->assertSame(0, $zipArchive->statusSys);

        $zipArchive->extractTo($tmpDir);
        $zipArchive->close();

        return $tmpDir;
    }

    protected function zipArchiveOpenErrorCodeName(int $code): string
    {
        switch($code) {
            case ZipArchive::ER_EXISTS: return 'ER_EXISTS';
            case ZipArchive::ER_INCONS: return 'ER_INCONS';
            case ZipArchive::ER_INVAL: return 'ER_INVAL';
            case ZipArchive::ER_MEMORY: return 'ER_MEMORY';
            case ZipArchive::ER_NOENT: return 'ER_NOENT';
            case ZipArchive::ER_NOZIP: return 'ER_NOZIP';
            case ZipArchive::ER_OPEN: return 'ER_OPEN';
            case ZipArchive::ER_READ: return 'ER_READ';
            case ZipArchive::ER_SEEK: return 'ER_SEEK';
            default: return 'unknown';
        }
    }

    protected function getTmpDir(): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'zipstreamtest');
        unlink($tmp);
        mkdir($tmp) or $this->fail('Failed to make directory');

        return $tmp;
    }

    /**
     * @return string[]
     */
    protected function getRecursiveFileList(string $path, bool $includeDirectories = false): array
    {
        $data = [];
        $path = (string)realpath($path);
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        $pathLen = strlen($path);
        foreach ($files as $file) {
            $filePath = $file->getRealPath();

            if (is_dir($filePath) && !$includeDirectories) {
                continue;
            }

            $data[] = substr($filePath, $pathLen + 1);
        }

        sort($data);

        return $data;
    }
}
