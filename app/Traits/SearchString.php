<?php

namespace App\Traits;

trait SearchString
{
    /**
     * Get the value of a name in search string.
     * Supports compact form (no spaces around operator) and spaced form.
     *
     * Examples (compact):
     *   search=type:customer year:2020 account_id:20
     *   search=issued_at>=2021-02-01 issued_at<=2021-02-10
     *
     * Examples (spaced):
     *   search=age > 30
     *   search=title : "Hello World"
     *
     * Returns a string for single-value operators (:, =) and an array when the
     * same column appears multiple times (e.g. range queries with > / <).
     * Surrounding quotes are stripped from returned values.
     */
    public function getSearchStringValue(
        string $name,
        string $default = '',
        string $input = ''
    ): string|array {
        $value = $default;

        $input = $input ?: request('search', '');

        // Normalise spaced operators (e.g. "age > 30") into compact form ("age>30")
        // so the rest of the parsing logic only needs to handle one format.
        $input = preg_replace('/\b(' . preg_quote($name, '/') . ')\s*(>=|<=|>|<|=|:)\s*/', '$1$2', $input);

        $columns = explode(' ', $input);

        foreach ($columns as $column) {
            // Operators ordered longest-first to avoid partial matches (>= before >).
            $variable = preg_split('/(>=|<=|>|<|=|:)/', $column, 2);

            if ($name === 'searchable' && count($variable) === 1 && preg_match('/^".*"$/', $variable[0])) {
                return trim($variable[0], '"');
            }

            if (empty($variable[0]) || $variable[0] !== $name || ! isset($variable[1]) || $variable[1] === '') {
                continue;
            }

            $extracted = trim($variable[1], '"\'');

            if (str_contains($column, ':')) {
                return $extracted;
            }

            if (! is_array($value)) {
                $value = [];
            }

            $value[] = $extracted;
        }

        return $value;
    }

    /**
     * Get the operator for a named column in the search string, mapped to an Eloquent-compatible operator.
     * The `:` operator maps to `=` (exact match).
     * The `not` prefix inverts the operator (e.g. `not amount>100` → `<=`).
     *
     * Examples without `not`:
     *   search=amount>=100   → '>='
     *   search=amount<=100   → '<='
     *   search=amount>100    → '>'
     *   search=amount<100    → '<'
     *   search=amount:100    → '='
     *   search=amount=100    → '='
     *
     * Examples with `not`:
     *   search=not amount>=100  → '<'
     *   search=not amount<=100  → '>'
     *   search=not amount>100   → '<='
     *   search=not amount<100   → '>='
     *   search=not amount:100   → '!='
     *   search=not amount=100   → '!='
     */
    public function getSearchStringOperator(
        string $name,
        string $default = '=',
        string $input = ''
    ): string {
        $input = $input ?: request('search', '');

        $columns = explode(' ', $input);

        foreach ($columns as $index => $column) {
            if (! str_starts_with($column, $name)) {
                continue;
            }

            $rest = substr($column, strlen($name));

            if (! preg_match('/^(>=|<=|>|<|=|:)/', $rest, $matches)) {
                continue;
            }

            $operator = $matches[1] === ':' ? '=' : $matches[1];
            $negated  = isset($columns[$index - 1]) && $columns[$index - 1] === 'not';

            if (! $negated) {
                return $operator;
            }

            return match ($operator) {
                '>='    => '<',
                '>'     => '<=',
                '<='    => '>',
                '<'     => '>=',
                default => '!=',
            };
        }

        return $default;
    }
}
