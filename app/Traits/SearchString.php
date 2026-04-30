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
        string $input = '',
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
        string $input = '',
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

    /**
     * Strip tokens from a search string whose keys are not recognised columns
     * for the given model class. Tokens whose keys appear in neither the model's
     * search-string config nor the default keywords / columns are removed before
     * the string is passed to usingSearchString().
     *
     * Falls back to returning $input unchanged when the model has no config entry.
     */
    public function stripUnknownSearchStringTokens(
        string $input,
        string $model_class,
    ): string {
        $model_config_columns = config('search-string.' . $model_class . '.columns', null);

        if ($model_config_columns === null) {
            return $input;
        }

        $valid_columns = array_unique(array_merge(
            array_keys($model_config_columns),
            array_values(config('search-string.default.keywords', [])),
            array_keys(config('search-string.default.columns', [])),
        ));

        // Collect key aliases from column configs.
        // A 'key' may be a plain string alias (e.g. 'invoiced_at') or a regex
        // pattern (e.g. '/^(invoiced_at|billed_at)$/').  Plain aliases are
        // merged into $valid_columns; regex patterns are kept separately and
        // tested with preg_match inside the callback below.
        $key_patterns = [];

        foreach ($model_config_columns as $column_config) {
            if (! is_array($column_config) || ! isset($column_config['key'])) {
                continue;
            }

            $key = $column_config['key'];

            if (is_string($key) && strlen($key) >= 2 && $key[0] === '/' && $key[-1] === '/') {
                $key_patterns[] = $key;
            } elseif (is_string($key)) {
                $valid_columns[] = $key;
            }
        }

        $valid_columns = array_unique($valid_columns);

        // Match optional "not " prefix + key + operator + value (quoted or unquoted)
        $input = preg_replace_callback(
            pattern: '/\b(?:not\s+)?(\w+)\s*(?:>=|<=|>|<|=|:)\s*(?:"[^"]*"|\S+)/',
            callback: function (array $matches) use ($valid_columns, $key_patterns): string {
                $token = $matches[1];

                if (in_array($token, $valid_columns, strict: true)) {
                    return $matches[0];
                }

                foreach ($key_patterns as $pattern) {
                    if (preg_match($pattern, $token)) {
                        return $matches[0];
                    }
                }

                return '';
            },
            subject: $input,
        );

        return trim(preg_replace('/\s+/', ' ', $input));
    }
}
