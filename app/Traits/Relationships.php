<?php

namespace App\Traits;

use App\Events\Common\RelationshipCounting;
use App\Events\Common\RelationshipDeleting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait Relationships
{
    public function countRelationships($model, $relationships)
    {
        $record = new \stdClass();
        $record->model = $model;
        $record->relationships = $relationships;

        event(new RelationshipCounting($record));

        $counter = [];

        foreach ((array) $record->relationships as $relationship => $text) {
            if (!$c = $model->$relationship()->count()) {
                continue;
            }

            $text = Str::contains($text, '::') ? $text : 'general.' . $text;
            $counter[] = $c . ' ' . strtolower(trans_choice($text, ($c > 1) ? 2 : 1));
        }

        return $counter;
    }

    /**
     * Mass delete relationships with events being fired.
     *
     * @param  $model
     * @param  $relationships
     *
     * @return void
     */
    public function deleteRelationships($model, $relationships)
    {
        $record = new \stdClass();
        $record->model = $model;
        $record->relationships = $relationships;

        event(new RelationshipDeleting($record));

        foreach ((array) $record->relationships as $relationship) {
            if (empty($model->$relationship)) {
                continue;
            }

            $items = [];
            $relation = $model->$relationship;

            if ($relation instanceof Collection) {
                $items = $relation->all();
            } else {
                $items[] = $relation;
            }

            foreach ((array) $items as $item) {
                $item->delete();
            }
        }
    }
}
