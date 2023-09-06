<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits;

use Illuminate\Database\Eloquent\Relations\Pivot;

trait IsConcatenable
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
        foreach ($this->throughParents as $throughParent) {
            $segments = explode(' as ', $throughParent->getTable());

            $class = get_class($throughParent);

            if (isset($segments[1])) {
                $class .= ' as '.$segments[1];
            } elseif ($throughParent instanceof Pivot) {
                $class = $throughParent->getTable();
            }

            $through[] = $class;
        }

        $foreignKeys = array_merge($foreignKeys, $this->foreignKeys);

        $localKeys = array_merge($localKeys, $this->localKeys);

        return [$through, $foreignKeys, $localKeys];
    }
}
