<?php

namespace Staudenmeir\EloquentHasManyDeep;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\Relation;
use RuntimeException;

trait ConcatenatesRelationships
{
    /**
     * Prepare a has-one-deep or has-many-deep relationship from existing relationships.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation[] $relations
     * @return array
     */
    protected function hasOneOrManyDeepFromRelations(array $relations)
    {
        if (is_array($relations[0])) {
            $relations = $relations[0];
        }

        $related = null;
        $through = [];
        $foreignKeys = [];
        $localKeys = [];

        foreach ($relations as $i => $relation) {
            $method = $this->hasOneOrManyDeepRelationMethod($relation);

            [$through, $foreignKeys, $localKeys] = $this->$method($relation, $through, $foreignKeys, $localKeys);

            if ($i === count($relations) - 1) {
                $related = get_class($relation->getRelated());
            } else {
                $through[] = $this->hasOneOrManyThroughParent($relation, $relations[$i + 1]);
            }
        }

        return [$related, $through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing belongs-to relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\BelongsTo $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromBelongsTo(BelongsTo $relation, array $through, array $foreignKeys, array $localKeys)
    {
        $foreignKeys[] = $relation->getOwnerKeyName();

        $localKeys[] = $relation->getForeignKeyName();

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing belongs-to-many relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\BelongsToMany $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromBelongsToMany(BelongsToMany $relation, array $through, array $foreignKeys, array $localKeys)
    {
        $through[] = $relation->getTable();

        $foreignKeys[] = $relation->getForeignPivotKeyName();
        $foreignKeys[] = $relation->getRelatedKeyName();

        $localKeys[] = $relation->getParentKeyName();
        $localKeys[] = $relation->getRelatedPivotKeyName();

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing has-one or has-many relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\HasOneOrMany $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromHasOneOrMany(HasOneOrMany $relation, array $through, array $foreignKeys, array $localKeys)
    {
        $foreignKeys[] = $relation->getQualifiedForeignKeyName();

        $localKeys[] = $relation->getLocalKeyName();

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing has-many-through relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\HasManyThrough $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromHasManyThrough(HasManyThrough $relation, array $through, array $foreignKeys, array $localKeys)
    {
        $through[] = get_class($relation->getParent());

        $foreignKeys[] = $relation->getFirstKeyName();
        $foreignKeys[] = $relation->getForeignKeyName();

        $localKeys[] = $relation->getLocalKeyName();
        $localKeys[] = $relation->getSecondLocalKeyName();

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing has-many-deep relationship.
     *
     * @param \Staudenmeir\EloquentHasManyDeep\HasManyDeep $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromHasManyDeep(HasManyDeep $relation, array $through, array $foreignKeys, array $localKeys)
    {
        foreach ($relation->getThroughParents() as $throughParent) {
            $segments = explode(' as ', $throughParent->getTable());

            $class = get_class($throughParent);

            if (isset($segments[1])) {
                $class .= ' as '.$segments[1];
            } elseif ($throughParent instanceof Pivot) {
                $class = $throughParent->getTable();
            }

            $through[] = $class;
        }

        $foreignKeys = array_merge($foreignKeys, $relation->getForeignKeys());

        $localKeys = array_merge($localKeys, $relation->getLocalKeys());

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing morph-one or morph-many relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\MorphOneOrMany $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromMorphOneOrMany(MorphOneOrMany $relation, array $through, array $foreignKeys, array $localKeys)
    {
        $foreignKeys[] = [$relation->getQualifiedMorphType(), $relation->getQualifiedForeignKeyName()];

        $localKeys[] = $relation->getLocalKeyName();

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing morph-to-many relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\MorphToMany $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromMorphToMany(MorphToMany $relation, array $through, array $foreignKeys, array $localKeys)
    {
        $through[] = $relation->getTable();

        if ($relation->getInverse()) {
            $foreignKeys[] = $relation->getForeignPivotKeyName();
            $foreignKeys[] = $relation->getRelatedKeyName();

            $localKeys[] = $relation->getParentKeyName();
            $localKeys[] = [$relation->getMorphType(), $relation->getRelatedPivotKeyName()];
        } else {
            $foreignKeys[] = [$relation->getMorphType(), $relation->getForeignPivotKeyName()];
            $foreignKeys[] = $relation->getRelatedKeyName();

            $localKeys[] = $relation->getParentKeyName();
            $localKeys[] = $relation->getRelatedPivotKeyName();
        }

        return [$through, $foreignKeys, $localKeys];
    }

    /**
     * Get the relationship method name.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     * @return string
     */
    protected function hasOneOrManyDeepRelationMethod(Relation $relation)
    {
        $classes = [
            BelongsTo::class,
            HasManyDeep::class,
            HasManyThrough::class,
            MorphOneOrMany::class,
            HasOneOrMany::class,
            MorphToMany::class,
            BelongsToMany::class,
        ];

        foreach ($classes as $class) {
            if ($relation instanceof $class) {
                return 'hasOneOrManyDeepFrom'.class_basename($class);
            }
        }

        throw new RuntimeException('This relationship is not supported.'); // @codeCoverageIgnore
    }

    /**
     * Prepare the through parent class from an existing relationship and its successor.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     * @param \Illuminate\Database\Eloquent\Relations\Relation $successor
     * @return string
     */
    protected function hasOneOrManyThroughParent(Relation $relation, Relation $successor)
    {
        $through = get_class($relation->getRelated());

        if (get_class($relation->getRelated()) === get_class($successor->getParent())) {
            $table = $successor->getParent()->getTable();

            $segments = explode(' as ', $table);

            if (isset($segments[1])) {
                $through .= ' as '.$segments[1];
            }
        }

        return $through;
    }
}
