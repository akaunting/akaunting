<?php

namespace Staudenmeir\EloquentHasManyDeep;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Traits\ConcatenatesRelationships;
use Staudenmeir\EloquentHasManyDeep\Eloquent\Traits\ReversesRelationships;

trait HasRelationships
{
    use ConcatenatesRelationships;
    use ReversesRelationships;

    /**
     * Define a has-many-deep relationship.
     *
     * @param string $related
     * @param array $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function hasManyDeep($related, array $through, array $foreignKeys = [], array $localKeys = [])
    {
        return $this->newHasManyDeep(...$this->hasOneOrManyDeep($related, $through, $foreignKeys, $localKeys));
    }

    /**
     * Define a has-one-deep relationship.
     *
     * @param string $related
     * @param array $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    public function hasOneDeep($related, array $through, array $foreignKeys = [], array $localKeys = [])
    {
        return $this->newHasOneDeep(...$this->hasOneOrManyDeep($related, $through, $foreignKeys, $localKeys));
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship.
     *
     * @param string $related
     * @param array $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeep($related, array $through, array $foreignKeys, array $localKeys)
    {
        $relatedSegments = preg_split('/\s+from\s+/i', $related);

        /** @var \Illuminate\Database\Eloquent\Model $relatedInstance */
        $relatedInstance = $this->newRelatedInstance($relatedSegments[0]);

        if (isset($relatedSegments[1])) {
            $relatedInstance->setTable($relatedSegments[1]);
        }

        $throughParents = $this->hasOneOrManyDeepThroughParents($through);

        $foreignKeys = $this->hasOneOrManyDeepForeignKeys($relatedInstance, $throughParents, $foreignKeys);

        $localKeys = $this->hasOneOrManyDeepLocalKeys($relatedInstance, $throughParents, $localKeys);

        return [$relatedInstance->newQuery(), $this, $throughParents, $foreignKeys, $localKeys];
    }

    /**
     * Prepare the through parents for a has-one-deep or has-many-deep relationship.
     *
     * @param array $through
     * @return array
     */
    protected function hasOneOrManyDeepThroughParents(array $through)
    {
        return array_map(function ($class) {
            $segments = preg_split('/\s+(as|from)\s+/i', $class, -1, PREG_SPLIT_DELIM_CAPTURE);

            $instance = $this->newRelatedDeepThroughInstance($segments[0]);

            if (isset($segments[1])) {
                $instance->setTable(
                    $segments[1] === 'as'
                        ? $instance->getTable().' as '.$segments[2]
                        : $segments[2]
                );
            }

            return $instance;
        }, $through);
    }

    /**
     * Create a new model instance for a related "deep through" model.
     *
     * @param string $class
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newRelatedDeepThroughInstance(string $class): Model
    {
        return str_contains($class, '\\')
            ? $this->newRelatedThroughInstance($class)
            : (new Pivot())->setTable($class);
    }

    /**
     * Prepare the foreign keys for a has-one-deep or has-many-deep relationship.
     *
     * @param \Illuminate\Database\Eloquent\Model $related
     * @param \Illuminate\Database\Eloquent\Model[] $throughParents
     * @param array $foreignKeys
     * @return array
     */
    protected function hasOneOrManyDeepForeignKeys(Model $related, array $throughParents, array $foreignKeys)
    {
        foreach (array_merge([$this], $throughParents) as $i => $instance) {
            /** @var \Illuminate\Database\Eloquent\Model $instance */
            if (!isset($foreignKeys[$i])) {
                if ($instance instanceof Pivot) {
                    $foreignKeys[$i] = ($throughParents[$i] ?? $related)->getKeyName();
                } else {
                    $foreignKeys[$i] = $instance->getForeignKey();
                }
            }
        }

        return $foreignKeys;
    }

    /**
     * Prepare the local keys for a has-one-deep or has-many-deep relationship.
     *
     * @param \Illuminate\Database\Eloquent\Model $related
     * @param \Illuminate\Database\Eloquent\Model[] $throughParents
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepLocalKeys(Model $related, array $throughParents, array $localKeys)
    {
        foreach (array_merge([$this], $throughParents) as $i => $instance) {
            /** @var \Illuminate\Database\Eloquent\Model $instance */
            if (!isset($localKeys[$i])) {
                if ($instance instanceof Pivot) {
                    $localKeys[$i] = ($throughParents[$i] ?? $related)->getForeignKey();
                } else {
                    $localKeys[$i] = $instance->getKeyName();
                }
            }
        }

        return $localKeys;
    }

    /**
     * Instantiate a new HasManyDeep relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $farParent
     * @param \Illuminate\Database\Eloquent\Model[] $throughParents
     * @param array $foreignKeys
     * @param array $localKeys
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    protected function newHasManyDeep(Builder $query, Model $farParent, array $throughParents, array $foreignKeys, array $localKeys)
    {
        return new HasManyDeep($query, $farParent, $throughParents, $foreignKeys, $localKeys);
    }

    /**
     * Instantiate a new HasOneDeep relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $farParent
     * @param \Illuminate\Database\Eloquent\Model[] $throughParents
     * @param array $foreignKeys
     * @param array $localKeys
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    protected function newHasOneDeep(Builder $query, Model $farParent, array $throughParents, array $foreignKeys, array $localKeys)
    {
        return new HasOneDeep($query, $farParent, $throughParents, $foreignKeys, $localKeys);
    }
}
