<?php

namespace Enlightn\Enlightn;

use Illuminate\Support\Arr;

class FileParser
{
    /**
     * Get the first line number in a file that matches any of the search strings
     * after the "after" search strings are matched.
     *
     * @param  string  $filePath
     * @param  string|array  $search
     * @param  array  $after
     * @return int|bool
     */
    public static function getLineNumber(string $filePath, $search, $after = [])
    {
        if (! file_exists($filePath)) {
            return false;
        }

        $handle = fopen($filePath, "r");

        $count = 0;

        $search = collect(Arr::wrap($search));
        $afterSearch = array_shift($after);

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $count++;

                if (! is_null($afterSearch)) {
                    if (strpos($line, $afterSearch) !== false) {
                        // remove the after search string when found
                        $afterSearch = array_shift($after);
                    }

                    // continue until the after searches have exhausted
                    continue;
                }

                if ($search->contains(function ($needle) use ($line) {
                    // finally if the needle is found, return the line number
                    return strpos($line, $needle) !== false;
                })) {
                    return $count;
                }
            }

            fclose($handle);
        }

        return 0;
    }
}
