<?php

namespace Staudenmeir\EloquentHasManyDeep;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\ExecutesQueries;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\HasEagerLimit;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\HasEagerLoading;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\HasExistenceQueries;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\IsConcatenable;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\JoinsThroughParents;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\RetrievesIntermediateTables;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\SupportsCompositeKeys;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits\IsCustomizable;
use Staudenmeir\EloquentHasManyDeepContracts\Interfaces\ConcatenableRelation;

/**
 * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
 * @extends \Illuminate\Database\Eloquent\Relations\HasManyThrough<TRelatedModel>
 */
class HasManyDeep extends HasManyThrough implements ConcatenableRelation
{
    use ExecutesQueries;
    use HasEagerLimit;
    use HasEagerLoading;
    use HasExistenceQueries;
    use IsConcatenable;
    use IsCustomizable;
    use JoinsThroughParents;
    use RetrievesIntermediateTables;
    use SupportsCompositeKeys;

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

        $firstKey = is_array($foreignKeys[0])
            ? $foreignKeys[0][1]
            : ($this->hasLeadingCompositeKey() ? $foreignKeys[0]->columns[0] : $foreignKeys[0]);

        $localKey = $this->hasLeadingCompositeKey() ? $localKeys[0]->columns[0] : $localKeys[0];

        parent::__construct($query, $farParent, $throughParents[0], $firstKey, $foreignKeys[1], $localKey, $localKeys[1]);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        if ($this->firstKey instanceof Closure || $this->localKey instanceof Closure) {
            $this->performJoin();
        } else {
            parent::addConstraints();
        }

        if (static::$constraints) {
            if ($this->firstKey instanceof Closure) {
                ($this->firstKey)($this->query);
            } elseif ($this->localKey instanceof Closure) {
                ($this->localKey)($this->query);
            } elseif (is_array($this->foreignKeys[0])) {
                $this->query->where(
                    $this->throughParent->qualifyColumn($this->foreignKeys[0][0]),
                    '=',
                    $this->farParent->getMorphClass()
                );
            } elseif ($this->hasLeadingCompositeKey()) {
                $this->addConstraintsWithCompositeKey();
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
     * Set the select clause for the relation query.
     *
     * @param array $columns
     * @return array
     */
    protected function shouldSelect(array $columns = ['*'])
    {
        if ($columns == ['*']) {
            $columns = [$this->related->getTable().'.*'];
        }

        $alias = 'laravel_through_key';

        if ($this->customThroughKeyCallback) {
            $throughKey = ($this->customThroughKeyCallback)($alias);

            if (is_array($throughKey)) {
                $columns = array_merge($columns, $throughKey);
            } else {
                $columns[] = $throughKey;
            }
        } else {
            $columns[] = $this->getQualifiedFirstKeyName() . " as $alias";
        }

        if ($this->hasLeadingCompositeKey()) {
            $columns = array_merge(
                $columns,
                $this->shouldSelectWithCompositeKey()
            );
        }

        return array_merge($columns, $this->intermediateColumns());
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
     * Get the far parent model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getFarParent(): Model
    {
        return $this->farParent;
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
