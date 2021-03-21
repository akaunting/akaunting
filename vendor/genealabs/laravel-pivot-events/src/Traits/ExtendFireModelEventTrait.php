<?php namespace GeneaLabs\LaravelPivotEvents\Traits;

trait ExtendFireModelEventTrait
{
    /**
     * Fire the given event for the model.
     *
     * @param string $event
     * @param bool   $halt
     *
     * @return mixed
     */
    public function fireModelEvent(
        $event,
        $halt = true,
        $relationName = null,
        $ids = [],
        $idsAttributes = []
    ) {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        $method = $halt
            ? 'until'
            : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method)
        );

        if (false === $result) {
            return false;
        }

        $payload = [
            0 => $this,
            'model' => $this,
            'relation' => $relationName,
            'pivotIds' => $ids,
            'pivotIdsAttributes' => $idsAttributes
        ];

        return $result
            ?: static::$dispatcher
                ->{$method}("eloquent.{$event}: " . static::class, $payload);
    }
}
