<?php

namespace App\Traits;

use Throwable;
use Illuminate\Support\Arr;

trait Translations
{
    public function findTranslation($keys, $number = 2)
    {
        $keys = Arr::wrap($keys);

        try {
            foreach ($keys as $key) {
                if (is_array($key)) {
                    $tmp = $key;

                    $key = $tmp[0];
                    $number = $tmp[1];
                }

                if ($key != trans_choice($key, $number)) {
                    return trans_choice($key, $number);
                }

                if ($key != trans($key)) {
                    return trans($key);
                }
            }
        } catch (Throwable $e) {
            return '';
        }

        return '';
    }
}
