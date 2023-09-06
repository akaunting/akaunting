<?php

namespace Staudenmeir\EloquentHasManyDeep\Eloquent\Relations\Traits;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

trait HasExistenceQueries
{
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
        $this->setRelationExistenceQueryAlias($parentQuery);

        if ($this->firstKey instanceof Closure || $this->localKey instanceof Closure) {
            $this->performJoin($query);

            $closureKey = $this->firstKey instanceof Closure ? $this->firstKey : $this->localKey;

            $closureKey($query, $parentQuery);

            return $query->select($columns);
        }

        $query = parent::getRelationExistenceQuery($query, $parentQuery, $columns);

        if (is_array($this->foreignKeys[0])) {
            $column = $this->throughParent->qualifyColumn($this->foreignKeys[0][0]);

            $query->where($column, '=', $this->farParent->getMorphClass());
        } elseif ($this->hasLeadingCompositeKey()) {
            $this->getRelationExistenceQueryWithCompositeKey($query);
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
        $hash = $this->getRelationCountHash();

        $query->from($query->getModel()->getTable().' as '.$hash);

        $this->performJoin($query);

        $query->getModel()->setTable($hash);

        return $query->select($columns)->whereColumn(
            $parentQuery->getQuery()->from.'.'.$this->localKey,
            '=',
            $this->getQualifiedFirstKeyName()
        );
    }

    /**
     * Set the table alias for a relation existence query if necessary.
     *
     * @param \Illuminate\Database\Eloquent\Builder $parentQuery
     * @return void
     */
    protected function setRelationExistenceQueryAlias(Builder $parentQuery): void
    {
        foreach ($this->throughParents as $throughParent) {
            if ($throughParent->getTable() === $parentQuery->getQuery()->from) {
                if (!in_array(HasTableAlias::class, class_uses_recursive($throughParent))) {
                    $traitClass = HasTableAlias::class;
                    $parentClass = get_class($throughParent);

                    throw new Exception(
                        <<<EOT
This query requires an additional trait. Please add the $traitClass trait to $parentClass.
See https://github.com/staudenmeir/eloquent-has-many-deep/issues/137 for details.
EOT
                    );
                }

                $table = $throughParent->getTable() . ' as ' . $this->getRelationCountHash();

                $throughParent->setTable($table);

                break;
            }
        }
    }
}
