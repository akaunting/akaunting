<?php

namespace Znck\Eloquent\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BelongsToThrough extends Relation
{
    use SupportsDefaultModels;

    /**
     * The column alias for the local key on the first "through" parent model.
     *
     * @var string
     */
    public const THROUGH_KEY = 'laravel_through_key';

    /**
     * The "through" parent model instances.
     *
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected $throughParents;

    /**
     * The foreign key prefix for the first "through" parent model.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The custom foreign keys on the relationship.
     *
     * @var array
     */
    protected $foreignKeyLookup;

    /**
     * Create a new belongs to through relationship instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $parent
     * @param \Illuminate\Database\Eloquent\Model[] $throughParents
     * @param string|null $localKey
     * @param string $prefix
     * @param array $foreignKeyLookup
     * @return void
     */
    public function __construct(Builder $query, Model $parent, array $throughParents, $localKey = null, $prefix = '', array $foreignKeyLookup = [])
    {
        $this->throughParents = $throughParents;
        $this->prefix = $prefix;
        $this->foreignKeyLookup = $foreignKeyLookup;

        parent::__construct($query, $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        $this->performJoins();

        if (static::$constraints) {
            $localValue = $this->parent[$this->getFirstForeignKeyName()];

            $this->query->where($this->getQualifiedFirstLocalKeyName(), '=', $localValue);
        }
    }

    /**
     * Set the join clauses on the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|null $query
     * @return void
     */
    protected function performJoins(Builder $query = null)
    {
        $query = $query ?: $this->query;

        foreach ($this->throughParents as $i => $model) {
            $predecessor = $i > 0 ? $this->throughParents[$i - 1] : $this->related;

            $first = $model->qualifyColumn($this->getForeignKeyName($predecessor));

            $second = $predecessor->getQualifiedKeyName();

            $query->join($model->getTable(), $first, '=', $second);

            if ($this->hasSoftDeletes($model)) {
                $column= $model->getQualifiedDeletedAtColumn();

                $query->withGlobalScope(__CLASS__ . ":$column", function (Builder $query) use ($column) {
                    $query->whereNull($column);
                });
            }
        }
    }

    /**
     * Get the foreign key for a model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return string
     */
    public function getForeignKeyName(Model $model = null)
    {
        $table = explode(' as ', ($model ?? $this->parent)->getTable())[0];

        if (array_key_exists($table, $this->foreignKeyLookup)) {
            return $this->foreignKeyLookup[$table];
        }

        return Str::singular($table).'_id';
    }

    /**
     * Determine whether a model uses SoftDeletes.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function hasSoftDeletes(Model $model)
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model));
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param array $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        $keys = $this->getKeys($models, $this->getFirstForeignKeyName());

        $this->query->whereIn($this->getQualifiedFirstLocalKeyName(), $keys);
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param \Illuminate\Database\Eloquent\Model[] $models
     * @param string $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param \Illuminate\Database\Eloquent\Model[] $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        $dictionary = $this->buildDictionary($results);

        foreach ($models as $model) {
            $key = $model[$this->getFirstForeignKeyName()];

            if (isset($dictionary[$key])) {
                $model->setRelation($relation, $dictionary[$key]);
            }
        }

        return $models;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @return array
     */
    protected function buildDictionary(Collection $results)
    {
        $dictionary = [];

        foreach ($results as $result) {
            $dictionary[$result[static::THROUGH_KEY]] = $result;

            unset($result[static::THROUGH_KEY]);
        }

        return $dictionary;
    }

    /**
     * Get the results of the relationship.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getResults()
    {
        return $this->first() ?: $this->getDefaultFor($this->parent);
    }

    /**
     * Execute the query and get the first result.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function first($columns = ['*'])
    {
        if ($columns === ['*']) {
            $columns = [$this->related->getTable().'.*'];
        }

        return $this->query->first($columns);
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        $columns = $this->query->getQuery()->columns ? [] : $columns;

        if ($columns === ['*']) {
            $columns = [$this->related->getTable().'.*'];
        }

        $columns[] = $this->getQualifiedFirstLocalKeyName().' as '.static::THROUGH_KEY;

        $this->query->addSelect($columns);

        return $this->query->get();
    }

    /**
     * Add the constraints for a relationship query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $parent
     * @param array|mixed $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRelationExistenceQuery(Builder $query, Builder $parent, $columns = ['*'])
    {
        $this->performJoins($query);

        $foreignKey = $parent->getQuery()->from.'.'.$this->getFirstForeignKeyName();

        return $query->select($columns)->whereColumn(
            $this->getQualifiedFirstLocalKeyName(),
            '=',
            $foreignKey
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

        foreach ($columns as $column) {
            $this->query->withoutGlobalScope(__CLASS__ . ":$column");
        }

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
     * Get the foreign key for the first "through" parent model.
     *
     * @return string
     */
    public function getFirstForeignKeyName()
    {
        return $this->prefix.$this->getForeignKeyName(end($this->throughParents));
    }

    /**
     * Get the qualified local key for the first "through" parent model.
     *
     * @return string
     */
    public function getQualifiedFirstLocalKeyName()
    {
        return end($this->throughParents)->getQualifiedKeyName();
    }

    /**
     * Make a new related instance for the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $parent
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newRelatedInstanceFor(Model $parent)
    {
        return $this->related->newInstance();
    }
}
