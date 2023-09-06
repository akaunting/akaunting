<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use RuntimeException;
use Staudenmeir\EloquentHasManyDeep\Eloquent\CompositeKey;

trait ConcatenatesNativeRelationships
{
    /**
     * Prepare a has-one-deep or has-many-deep relationship from an existing belongs-to relationship.
     *
     * @param \Illuminate\Database\Eloquent\Relations\BelongsTo $relation
     * @param \Illuminate\Database\Eloquent\Model[] $through
     * @param array $foreignKeys
     * @param array $localKeys
     * @return array
     */
    protected function hasOneOrManyDeepFromBelongsTo(
        BelongsTo $relation,
        array $through,
        array $foreignKeys,
        array $localKeys
    ) {
        if (is_array($relation->getOwnerKeyName())) {
            // https://github.com/topclaudy/compoships
            $foreignKeys[] = new CompositeKey(
                ...(array)$relation->getOwnerKeyName()
            );

            $localKeys[] = new CompositeKey(
                ...(array)$relation->getForeignKeyName()
            );
        } else {
            $foreignKeys[] = $relation->getOwnerKeyName();

            $localKeys[] = $relation->getForeignKeyName();
        }

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
    protected function hasOneOrManyDeepFromBelongsToMany(
        BelongsToMany $relation,
        array $through,
        array $foreignKeys,
        array $localKeys
    ) {
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
    protected function hasOneOrManyDeepFromHasOneOrMany(
        HasOneOrMany $relation,
        array $through,
        array $foreignKeys,
        array $localKeys
    ) {
        if (is_array($relation->getForeignKeyName())) {
            // https://github.com/topclaudy/compoships
            $foreignKeys[] = new CompositeKey(
                ...(array)$relation->getForeignKeyName()
            );

            $localKeys[] = new CompositeKey(
                ...(array)$relation->getLocalKeyName()
            );
        } else {
            $foreignKeys[] = $relation->getForeignKeyName();

            $localKeys[] = $relation->getLocalKeyName();
        }

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
    protected function hasOneOrManyDeepFromHasManyThrough(
        HasManyThrough $relation,
        array $through,
        array $foreignKeys,
        array $localKeys
    ) {
        $through[] = get_class($relation->getParent());

        $foreignKeys[] = $relation->getFirstKeyName();
        $foreignKeys[] = $relation->getForeignKeyName();

        $localKeys[] = $relation->getLocalKeyName();
        $localKeys[] = $relation->getSecondLocalKeyName();

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
    protected function hasOneOrManyDeepFromMorphOneOrMany(
        MorphOneOrMany $relation,
        array $through,
        array $foreignKeys,
        array $localKeys
    ) {
        $foreignKeys[] = [$relation->getQualifiedMorphType(), $relation->getForeignKeyName()];

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
    protected function hasOneOrManyDeepFromMorphToMany(
        MorphToMany $relation,
        array $through,
        array $foreignKeys,
        array $localKeys
    ) {
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
            HasManyThrough::class,
            MorphOneOrMany::class,
            HasOneOrMany::class,
            MorphToMany::class,
            BelongsToMany::class,
        ];

        foreach ($classes as $class) {
            if ($relation instanceof $class) {
                return 'hasOneOrManyDeepFrom' . class_basename($class);
            }
        }

        throw new RuntimeException('This relationship is not supported.'); // @codeCoverageIgnore
    }
}
