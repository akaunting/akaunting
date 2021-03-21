<?php

namespace Staudenmeir\EloquentHasManyDeep;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

trait HasRelationships
{
    use ConcatenatesRelationships;

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
     * Define a has-many-deep relationship from existing relationships.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation ...$relations
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function hasManyDeepFromRelations(...$relations)
    {
        return $this->hasManyDeep(...$this->hasOneOrManyDeepFromRelations($relations));
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
     * Define a has-one-deep relationship from existing relationships.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation ...$relations
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    public function hasOneDeepFromRelations(...$relations)
    {
        return $this->hasOneDeep(...$this->hasOneOrManyDeepFromRelations($relations));
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
        /** @var \Illuminate\Database\Eloquent\Model $relatedInstance */
        $relatedInstance = $this->newRelatedInstance($related);

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
            $segments = preg_split('/\s+as\s+/i', $class);

            $instance = Str::contains($segments[0], '\\')
                ? new $segments[0]
                : (new Pivot)->setTable($segments[0]);

            if (isset($segments[1])) {
                $instance->setTable($instance->getTable().' as '.$segments[1]);
            }

            return $instance;
        }, $through);
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
