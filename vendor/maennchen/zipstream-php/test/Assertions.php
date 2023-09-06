<?php

declare(strict_types=1);

namespace ZipStream\Test;

trait Assertions
{
    protected function assertFileContains(string $filePath, string $needle): void
    {
        $last = '';

        $handle = fopen($filePath, 'r');
        while (!feof($handle)) {
            $line = fgets($handle, 1024);

            if(str_contains($last . $line, $needle)) {
                fclose($handle);
                return;
            }

            $last = $line;
        }

        fclose($handle);

        $this->fail("File {$filePath} must contain {$needle}");
    }

    protected function assertFileDoesNotContain(string $filePath, string $needle): void
    {
        $last = '';

        $handle = fopen($filePath, 'r');
        while (!feof($handle)) {
            $line = fgets($handle, 1024);

            if(str_contains($last . $line, $needle)) {
                fclose($handle);

                $this->fail("File {$filePath} must not contain {$needle}");
            }

            $last = $line;
        }

        fclose($handle);
    }
}
