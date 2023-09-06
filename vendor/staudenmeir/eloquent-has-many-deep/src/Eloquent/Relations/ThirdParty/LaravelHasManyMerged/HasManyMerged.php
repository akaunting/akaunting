<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\ThirdParty\LaravelHasManyMerged;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Korridor\LaravelHasManyMerged\HasManyMerged as Base;
use Staudenmeir\EloquentHasManyDeepContracts\Interfaces\ConcatenableRelation;

/**
 * @copyright Based on package by Constantin Graf (korridor): https://github.com/korridor/laravel-has-many-merged
 */
class HasManyMerged extends Base implements ConcatenableRelation
{
    /**
     * Append the relation's through parents, foreign and local keys to a deep relationship.
     *
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @param int $position
     * @return array
     */
    public function appendToDeepRelationship(array $through, array $foreignKeys, array $localKeys, int $position): array
    {
        if ($position === 0) {
            $foreignKeys[] = function (Builder $query, Builder $parentQuery = null) {
                if ($parentQuery) {
                    $this->getRelationExistenceQuery($this->query, $parentQuery);
                }

                $query->mergeConstraintsFrom($this->query);
            };

            $localKeys[] = $this->localKey;
        } else {
            $foreignKeys[] = function (Builder $query, JoinClause $join) {
                $join->on(
                    function (JoinClause $join) {
                        foreach ($this->foreignKeys as $foreignKey) {
                            $join->orOn($foreignKey, '=', $this->getQualifiedParentKeyName());
                        }
                    }
                );
            };

            $localKeys[] = null;
        }

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Get the custom through key for an eager load of the relation.
     *
     * @param string $alias
     * @return array
     */
    public function getThroughKeyForDeepRelationships(string $alias): array
    {
        $columns = [];

        foreach ($this->foreignKeys as $i => $foreignKey) {
            $columns[] = "$foreignKey as $alias" . ($i > 0 ? "_$i" : '');
        }

        return $columns;
    }

    /**
     * Set the constraints for an eager load of the deep relation.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $models
     * @return void
     */
    public function addEagerConstraintsToDeepRelationship(Builder $query, array $models): void
    {
        $this->addEagerConstraints($models);

        $query->mergeConstraintsFrom($this->query);
    }

    /**
     * Match the eagerly loaded results for a deep relationship to their parents.
     *
     * @param array $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string $relation
     * @return array
     */
    public function matchResultsForDeepRelationship(array $models, Collection $results, string $relation): array
    {
        $dictionary = $this->buildDictionaryForDeepRelationship($results);

        foreach ($models as $model) {
            if (isset($dictionary[$key = $model->getAttribute($this->localKey)])) {
                $model->setRelation(
                    $relation,
                    $this->getRelated()->newCollection($dictionary[$key])->unique($this->getRelated()->getKeyName())
                );
            }
        }

        return $models;
    }

    /**
     * Build the model dictionary for a deep relation.
     *
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @return array
     */
    protected function buildDictionaryForDeepRelationship(Collection $results): array
    {
        $dictionary = [];

        $foreignKeyNames = array_map(
            fn ($i) => 'laravel_through_key' . ($i > 0 ? "_$i" : ''),
            range(0, count($this->foreignKeys) - 1)
        );

        foreach ($results as $result) {
            foreach ($foreignKeyNames as $foreignKeyName) {
                $foreignKeyValue = $result->{$foreignKeyName};
                if (!isset($dictionary[$foreignKeyValue])) {
                    $dictionary[$foreignKeyValue] = [];
                }

                $dictionary[$foreignKeyValue][] = $result;
            }
        }

        return $dictionary;
    }

    /**
     * Create a new instance of the relation from a base relation instance.
     *
     * @param \Korridor\LaravelHasManyMerged\HasManyMerged $relation
     * @return static
     */
    public static function fromBaseRelation(Base $relation): static
    {
        return new static(
            $relation->getQuery(),
            $relation->getParent(),
            $relation->getQualifiedForeignKeyNames(),
            (fn () => $this->localKey)->call($relation)
        );
    }
}
