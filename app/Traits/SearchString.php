<?php

namespace App\Traits;

trait SearchString
{
    /**
     * Get the value of a name in search string
     * Example: search=type:customer year:2020 account_id:20
     * Example: issued_at>=2021-02-01 issued_at<=2021-02-10 account_id:49
     */
    public function getSearchStringValue(string $name, string $default = '', string $input = ''): string|array
    {
        $value = $default;

        $input = $input ?: request('search', '');

        // $manager = $this->getSearchStringManager();
        // $parsed = $manager->parse($input);

        $columns = explode(' ', $input);

        foreach ($columns as $column) {
            $variable = preg_split('/:|>?<?=/', $column);

            if (empty($variable[0]) || ($variable[0] != $name) || empty($variable[1])) {
                continue;
            }

            if (strpos($column, ':')) {
                $value = $variable[1];

                break;
            }

            if (! is_array($value)) {
                $value = [];
            }

            $value[] = $variable[1];
        }

        return $value;
    }
}
