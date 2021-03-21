<?php

namespace Lorisleiva\LaravelSearchString\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait SearchStringOptions
{
    /** @var Collection */
    protected $options;

    /** @var array */
    protected static $fallbackOptions = [
        'columns' => [],
        'keywords' => [
            'order_by' => 'sort',
            'select' => 'fields',
            'limit' => 'limit',
            'offset' => 'from',
        ],
    ];

    protected function generateOptions(Model $model): void
    {
        $options = array_replace_recursive(
            static::$fallbackOptions,
            Arr::get(config('search-string'), 'default', []),
            Arr::get(config('search-string'), get_class($model), []),
            $model->getSearchStringOptions() ?? []
        );

        $this->options = $this->parseOptions($options, $model);
    }

    protected function parseOptions(array $options, Model $model): Collection
    {
        return collect([
            'columns' => $this->parseColumns($options, $model),
            'keywords' => $this->parseKeywords($options),
        ]);
    }

    protected function parseColumns(array $options, Model $model): Collection
    {
        return collect(Arr::get($options, 'columns', []))
            ->mapWithKeys(function ($rule, $column) {
                return $this->parseNonAssociativeColumn($rule, $column);
            })
            ->map(function ($rule, $column) use ($model) {
                return new ColumnRule($model, $column, $rule);
            });
    }

    protected function parseKeywords(array $options): Collection
    {
        return collect(Arr::get($options, 'keywords', []))
            ->mapWithKeys(function ($rule, $keyword) {
                return $this->parseNonAssociativeColumn($rule, $keyword);
            })
            ->map(function ($rule, $keyword) {
                return new KeywordRule($keyword, $rule);
            });
    }

    protected function parseNonAssociativeColumn($rule, $column): array
    {
        return is_string($column) ? [$column => $rule] : [$rule => null];
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function getKeywordRules(): Collection
    {
        return $this->options->get('keywords');
    }

    public function getColumnRules(): Collection
    {
        return $this->options->get('columns');
    }

    public function getKeywordRule($key): ?KeywordRule
    {
        return $this->getKeywordRules()->first(function ($rule) use ($key) {
            return $rule->match($key);
        });
    }

    public function getColumnRule($key): ?ColumnRule
    {
        return $this->getColumnRules()->first(function ($rule) use ($key) {
            return $rule->match($key);
        });
    }

    public function getRule($key): ?Rule
    {
        if ($rule = $this->getKeywordRule($key)) {
            return $rule;
        }

        return $this->getColumnRule($key);
    }

    public function getColumns(): Collection
    {
        return $this->getColumnRules()->reject->relationship->keys();
    }

    public function getSearchables(): Collection
    {
        return $this->getColumnRules()->filter->searchable->keys();
    }
}
