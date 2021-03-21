<?php

namespace Staudenmeir\EloquentHasManyDeep;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class HasManyDeep extends HasManyThrough
{
    use HasEagerLimit, RetrievesIntermediateTables;

    /**
     * The "through" parent model instances.
     *
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected $throughParents;

    /**
     * The foreign keys on the relationship.
     *
     * @var array
     */
    protected $foreignKeys;

    /**
     * The local keys on the relationship.
     *
     * @var array
     */
    protected $localKeys;

    /**
     * Create a new has many deep relationship instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $farParent
     * @param \Illuminate\Database\Eloquent\Model[] $throughParents
     * @param array $foreignKeys
     * @param array $localKeys
     * @return void
     */
    public function __construct(Builder $query, Model $farParent, array $throughParents, array $foreignKeys, array $localKeys)
    {
        $this->throughParents = $throughParents;
        $this->foreignKeys = $foreignKeys;
        $this->localKeys = $localKeys;

        $firstKey = is_array($foreignKeys[0]) ? $foreignKeys[0][1] : $foreignKeys[0];

        parent::__construct($query, $farParent, $throughParents[0], $firstKey, $foreignKeys[1], $localKeys[0], $localKeys[1]);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        parent::addConstraints();

        if (static::$constraints) {
            if (is_array($this->foreignKeys[0])) {
                $column = $this->throughParent->qualifyColumn($this->foreignKeys[0][0]);

                $this->query->where($column, '=', $this->farParent->getMorphClass());
            }
        }
    }

    /**
     * Set the join clauses on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|null $query
     * @return void
     */
    protected function performJoin(Builder $query = null)
    {
        $query = $query ?: $this->query;

        $throughParents = array_reverse($this->throughParents);
        $foreignKeys = array_reverse($this->foreignKeys);
        $localKeys = array_reverse($this->localKeys);

        $segments = explode(' as ', $query->getQuery()->from);

        $alias = $segments[1] ?? null;

        foreach ($throughParents as $i => $throughParent) {
            $predecessor = $throughParents[$i - 1] ?? $this->related;

            $prefix = $i === 0 && $alias ? $alias.'.' : '';

            $this->joinThroughParent($query, $throughParent, $predecessor, $foreignKeys[$i], $localKeys[$i], $prefix);
        }
    }

    /**
     * Join a through parent table.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $throughParent
     * @param \Illuminate\Database\Eloquent\Model $predecessor
     * @param array|string $foreignKey
     * @param array|string $localKey
     * @param string $prefix
     * @return void
     */
    protected function joinThroughParent(Builder $query, Model $throughParent, Model $predecessor, $foreignKey, $localKey, $prefix)
    {
        if (is_array($localKey)) {
            $query->where($throughParent->qualifyColumn($localKey[0]), '=', $predecessor->getMorphClass());

            $localKey = $localKey[1];
        }

        $first = $throughParent->qualifyColumn($localKey);

        if (is_array($foreignKey)) {
            $query->where($predecessor->qualifyColumn($foreignKey[0]), '=', $throughParent->getMorphClass());

            $foreignKey = $foreignKey[1];
        }

        $second = $predecessor->qualifyColumn($prefix.$foreignKey);

        $query->join($throughParent->getTable(), $first, '=', $second);

        if ($this->throughParentInstanceSoftDeletes($throughParent)) {
            $query->whereNull($throughParent->getQualifiedDeletedAtColumn());
        }
    }

    /**
     * Determine whether a "through" parent instance of the relation uses SoftDeletes.
     *
     * @param \Illuminate\Database\Eloquent\Model $instance
     * @return bool
     */
    public function throughParentInstanceSoftDeletes(Model $instance)
    {
        return in_array(SoftDeletes::class, class_uses_recursive($instance));
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param array $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        parent::addEagerConstraints($models);

        if (is_array($this->foreignKeys[0])) {
            $column = $this->throughParent->qualifyColumn($this->foreignKeys[0][0]);

            $this->query->where($column, '=', $this->farParent->getMorphClass());
        }
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        $models = parent::get($columns);

        $this->hydrateIntermediateRelations($models->all());

        return $models;
    }

    /**
     * Get a paginator for the "select" statement.
     *
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $columns = $this->shouldSelect($columns);

        $columns = array_diff($columns, [$this->getQualifiedFirstKeyName().' as laravel_through_key']);

        $this->query->addSelect($columns);

        return tap($this->query->paginate($perPage, $columns, $pageName, $page), function (Paginator $paginator) {
            $this->hydrateIntermediateRelations($paginator->items());
        });
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $columns = $this->shouldSelect($columns);

        $columns = array_diff($columns, [$this->getQualifiedFirstKeyName().' as laravel_through_key']);

        $this->query->addSelect($columns);

        return tap($this->query->simplePaginate($perPage, $columns, $pageName, $page), function (Paginator $paginator) {
            $this->hydrateIntermediateRelations($paginator->items());
        });
    }

    /**
     * Set the select clause for the relation query.
     *
     * @param array $columns
     * @return array
     */
    protected function shouldSelect(array $columns = ['*'])
    {
        return array_merge(parent::shouldSelect($columns), $this->intermediateColumns());
    }

    /**
     * Chunk the results of the query.
     *
     * @param int $count
     * @param callable $callback
     * @return bool
     */
    public function chunk($count, callable $callback)
    {
        return $this->prepareQueryBuilder()->chunk($count, function (Collection $results) use ($callback) {
            $this->hydrateIntermediateRelations($results->all());

            return $callback($results);
        });
    }

    /**
     * Add the constraints for a relationship query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder $parentQuery
     * @param array|mixed $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        $query = parent::getRelationExistenceQuery($query, $parentQuery, $columns);

        if (is_array($this->foreignKeys[0])) {
            $column = $this->throughParent->qualifyColumn($this->foreignKeys[0][0]);

            $query->where($column, '=', $this->farParent->getMorphClass());
        }

        return $query;
    }

    /**
     * Add the constraints for a relationship query on the same table.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder $parentQuery
     * @param array|mixed $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRelationExistenceQueryForSelfRelation(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        $query->from($query->getModel()->getTable().' as '.$hash = $this->getRelationCountHash());

        $this->performJoin($query);

        $query->getModel()->setTable($hash);

        return $query->select($columns)->whereColumn(
            $parentQuery->getQuery()->from.'.'.$query->getModel()->getKeyName(),
            '=',
            $this->getQualifiedFirstKeyName()
        );
    }

    /**
     * Restore soft-deleted models.
     *
     * @param array|string ...$columns
     * @return $this
     */
    public function withTrashed(...$columns)
    {
        if (empty($columns)) {
            $this->query->withTrashed();

            return $this;
        }

        if (is_array($columns[0])) {
            $columns = $columns[0];
        }

        $this->query->getQuery()->wheres = collect($this->query->getQuery()->wheres)
            ->reject(function ($where) use ($columns) {
                return $where['type'] === 'Null' && in_array($where['column'], $columns);
            })->values()->all();

        return $this;
    }

    /**
     * Get the "through" parent model instances.
     *
     * @return \Illuminate\Database\Eloquent\Model[]
     */
    public function getThroughParents()
    {
        return $this->throughParents;
    }

    /**
     * Get the foreign keys on the relationship.
     *
     * @return array
     */
    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }

    /**
     * Get the local keys on the relationship.
     *
     * @return array
     */
    public function getLocalKeys()
    {
        return $this->localKeys;
    }
}
