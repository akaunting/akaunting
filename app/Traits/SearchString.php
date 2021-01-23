<?php

namespace App\Traits;

trait SearchString
{
    /**
     * Get the value of a name in search string
     * Example: search=type:customer year:2020 account_id:20
     *
     * @return string
     */
    public function getSearchStringValue($name, $default = '', $input = null)
    {
        $value = $default;

        if (is_null($input)) {
            $input = request('search');
        }

        //$manager = $this->getSearchStringManager();
        //$parsed = $manager->parse($input);

        $columns = explode(' ', $input);

        foreach ($columns as $column) {
            $variable = explode(':', $column);

            if (empty($variable[0]) || ($variable[0] != $name) || empty($variable[1])) {
                continue;
            }

            $value = $variable[1];

            break;
        }

        return $value;
    }
}
