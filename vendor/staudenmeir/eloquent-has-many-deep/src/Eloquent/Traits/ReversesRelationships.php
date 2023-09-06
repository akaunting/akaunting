<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

trait ReversesRelationships
{
    /**
     * Define a has-many-deep relationship by reversing an existing deep relationship.
     *
     * @param \Staudenmeir\EloquentHasManyDeep\HasManyDeep $relation
     * @return \Staudenmeir\EloquentHasManyDeep\HasManyDeep
     */
    public function hasManyDeepFromReverse(HasManyDeep $relation): HasManyDeep
    {
        return $this->hasManyDeep(...$this->hasOneOrManyDeepFromReverse($relation));
    }

    /**
     * Define a has-one-deep relationship by reversing an existing deep relationship.
     *
     * @param \Staudenmeir\EloquentHasManyDeep\HasManyDeep $relation
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    public function hasOneDeepFromReverse(HasManyDeep $relation): HasOneDeep
    {
        return $this->hasOneDeep(...$this->hasOneOrManyDeepFromReverse($relation));
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship by reversing an existing deep relationship.
     *
     * @param \Staudenmeir\EloquentHasManyDeep\HasManyDeep $relation
     * @return array
     */
    protected function hasOneOrManyDeepFromReverse(HasManyDeep $relation): array
    {
        $related = $relation->getFarParent()::class;

        $through = [];

        foreach (array_reverse($relation->getThroughParents()) as $throughParent) {
            $through[] = $this->hasOneOrManyDeepFromReverseThroughClass($throughParent);
        }

        $foreignKeys = array_reverse(
            $relation->getLocalKeys()
        );

        $localKeys = array_reverse(
            $relation->getForeignKeys()
        );

        return [$related, $through, $foreignKeys, $localKeys];
    }

    /**
     * Prepare a has-one-deep or has-many-deep relationship through class.
     *
     * @param \Illuminate\Database\Eloquent\Model $throughParent
     * @return string
     */
    protected function hasOneOrManyDeepFromReverseThroughClass(Model $throughParent): string
    {
        $table = $throughParent->getTable();

        $segments = preg_split('/\s+as\s+/i', $table);

        if ($throughParent instanceof Pivot) {
            if (isset($segments[1])) {
                $class = $throughParent::class . " as $segments[1]";
            } else {
                $class = $table;
            }
        } else {
            $class = $throughParent::class;

            if (isset($segments[1])) {
                $class .= " as $segments[1]";
            } elseif ($table !== (new $throughParent())->getTable()) {
                $class .= " from $table";
            }
        }

        return $class;
    }
}
