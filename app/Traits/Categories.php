<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Categories
{
    public function getCategoryTypes()
    {
        $types = [];
        $configs = config('type.category');

        foreach ($configs as $type => $attr) {
            $plural_type = Str::plural($type);

            $name = $attr['translation']['prefix'] . '.' . $plural_type;

            if (!empty($attr['alias'])) {
                $name = $attr['alias'] . '::' . $name;
            }

            $types[$type] = trans_choice($name, 1);
        }

        return $types;
    }
}
