<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits;

use Illuminate\Database\Eloquent\Collection;

trait HasEagerLoading
{
    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param array $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        if ($this->customEagerConstraintsCallback) {
            ($this->customEagerConstraintsCallback)($this->query, $models);
            return;
        }

        if ($this->hasLeadingCompositeKey()) {
            $this->addEagerConstraintsWithCompositeKey($models);
        } else {
            parent::addEagerConstraints($models);

            if (is_array($this->foreignKeys[0])) {
                $this->query->where(
                    $this->throughParent->qualifyColumn($this->foreignKeys[0][0]),
                    '=',
                    $this->farParent->getMorphClass()
                );
            }
        }
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param array $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        if ($this->customEagerMatchingCallbacks) {
            foreach ($this->customEagerMatchingCallbacks as $callback) {
                $models = $callback($models, $results, $relation);
            }

            return $models;
        }

        if ($this->hasLeadingCompositeKey()) {
            return $this->matchWithCompositeKey($models, $results, $relation);
        }

        return parent::match($models, $results, $relation);
    }
}
