<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\Eloquent\CompositeKey;

trait SupportsCompositeKeys
{
    /**
     * Determine whether the relationship starts with a composite key.
     *
     * @return bool
     */
    protected function hasLeadingCompositeKey(): bool
    {
        return $this->localKeys[0] instanceof CompositeKey;
    }

    /**
     * Set the base constraints on the relation query for a leading composite key.
     *
     * @return void
     */
    protected function addConstraintsWithCompositeKey(): void
    {
        $columns = array_slice($this->foreignKeys[0]->columns, 1, null, true);

        foreach ($columns as $i => $column) {
            $this->query->where(
                $this->throughParent->qualifyColumn($column),
                '=',
                $this->farParent[$this->localKeys[0]->columns[$i]]
            );
        }
    }

    /**
     * Set the constraints for an eager load of the relation for a leading composite key.
     *
     * @param array $models
     * @return void
     */
    protected function addEagerConstraintsWithCompositeKey(array $models): void
    {
        $keys = collect($models)->map(
            function (Model $model) {
                return array_map(
                    fn (string $column) => $model[$column],
                    $this->localKeys[0]->columns
                );
            }
        )->values()->unique(null, true)->all();

        $this->query->where(
            function (Builder $query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhere(
                        function (Builder $query) use ($key) {
                            foreach ($this->foreignKeys[0]->columns as $i => $column) {
                                $query->where(
                                    $this->throughParent->qualifyColumn($column),
                                    '=',
                                    $key[$i]
                                );
                            }
                        }
                    );
                }
            }
        );
    }

    /**
     * Match the eagerly loaded results to their parents for a leading composite key.
     *
     * @param array $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string $relation
     * @return array
     */
    protected function matchWithCompositeKey(array $models, Collection $results, string $relation): array
    {
        $dictionary = $this->buildDictionaryWithCompositeKey($results);

        foreach ($models as $model) {
            $values = [];

            foreach ($this->localKeys[0]->columns as $column) {
                $values[] = $this->getDictionaryKey(
                    $model->getAttribute($column)
                );
            }

            $key = implode("\0", $values);

            if (isset($dictionary[$key])) {
                $model->setRelation(
                    $relation,
                    $this->related->newCollection($dictionary[$key])
                );
            }
        }

        return $models;
    }

    /**
     * Build model dictionary keyed by the relation's composite foreign key.
     *
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @return array
     */
    protected function buildDictionaryWithCompositeKey(Collection $results): array
    {
        $dictionary = [];

        foreach ($results as $result) {
            $values = [];

            foreach ($this->foreignKeys[0]->columns as $i => $column) {
                $alias = 'laravel_through_key' . ($i > 0 ? "_$i" : '');

                $values[] = $result->$alias;
            }

            $values = implode("\0", $values);

            $dictionary[$values][] = $result;
        }

        return $dictionary;
    }

    /**
     * Get the columns to select for a leading composite key.
     *
     * @return array
     */
    protected function shouldSelectWithCompositeKey(): array
    {
        $columns = array_slice($this->foreignKeys[0]->columns, 1, null, true);

        return array_map(
            fn ($column, $i) => $this->throughParent->qualifyColumn($column) . " as laravel_through_key_$i",
            $columns,
            array_keys($columns)
        );
    }

    /**
     * Add the constraints for a relationship query for a leading composite key.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function getRelationExistenceQueryWithCompositeKey(Builder $query): void
    {
        $columns = array_slice($this->localKeys[0]->columns, 1, null, true);

        foreach ($columns as $i => $column) {
            $query->whereColumn(
                $this->farParent->qualifyColumn($column),
                '=',
                $this->throughParent->qualifyColumn($this->foreignKeys[0]->columns[$i])
            );
        }
    }
}
