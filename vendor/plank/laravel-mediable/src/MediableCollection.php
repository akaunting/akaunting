<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Collection of Mediable Models.
 */
class MediableCollection extends Collection
{
    /**
     * Lazy eager load media attached to items in the collection.
     * @param string|string[] $tags
     * If one or more tags are specified, only media attached to those tags will be loaded.
     * @param bool $matchAll If true, only load media attached to all tags simultaneously
     * @param bool $withVariants If true, also load the variants and/or originalMedia relation of each Media
     * @return $this
     */
    public function loadMedia(
        $tags = [],
        bool $matchAll = false,
        bool $withVariants = false
    ): self {
        if ($this->isEmpty()) {
            return $this;
        }

        $tags = (array)$tags;

        if (empty($tags)) {
            if ($withVariants) {
                return $this->load(['media.originalMedia.variants', 'media.variants']);
            } else {
                return $this->load('media');
            }
        }

        if ($matchAll) {
            $closure = function (MorphToMany $q) use ($tags, $withVariants) {
                $this->addMatchAllToEagerLoadQuery($q, $tags);

                if ($withVariants) {
                    $q->with(['originalMedia.variants', 'variants']);
                }
            };
            $closure = Closure::bind($closure, $this->first(), $this->first());

            return $this->load(['media' => $closure]);
        }


        return $this->load(
            [
                'media' => function (MorphToMany $q) use ($tags, $withVariants) {
                    $q->wherePivotIn('tag', $tags);

                    if ($withVariants) {
                        $q->with(['originalMedia.variants', 'variants']);
                    }
                }
            ]
        );
    }

    /**
     * Lazy eager load media attached to items in the collection, as well as their variants.
     * @param string|string[] $tags
     * If one or more tags are specified, only media attached to those tags will be loaded.
     * @param bool $matchAll If true, only load media attached to all tags simultaneously
     * @return $this
     */
    public function loadMediaWithVariants($tags = [], bool $matchAll = false): self
    {
        return $this->loadMedia($tags, $matchAll, true);
    }

    /**
     * Lazy eager load media attached to items in the collection bound all of the provided
     * tags simultaneously.
     * @param  string|string[] $tags
     * @param bool $withVariants If true, also load the variants and/or originalMedia relation of each Media
     * If one or more tags are specified, only media attached to all of those tags will be loaded.
     * @return $this
     */
    public function loadMediaMatchAll($tags = [], bool $withVariants = false): self
    {
        return $this->loadMedia($tags, true, $withVariants);
    }

    /**
     * Lazy eager load media attached to items in the collection bound all of the provided
     * tags simultaneously, as well as the variants of those media.
     * @param  string|string[] $tags
     * If one or more tags are specified, only media attached to all of those tags will be loaded.
     * @return $this
     */
    public function loadMediaWithVariantsMatchAll($tags = []): self
    {
        return $this->loadMedia($tags, true, true);
    }

    public function delete(): void
    {
        if (count($this) == 0) {
            return;
        }

        /** @var MorphToMany $relation */
        $relation = $this->first()->media();
        $query = $relation->newPivotStatement();
        $classes = [];

        $this->each(
            function (Model $item) use ($query, $relation, &$classes) {
                // collect list of ids of each class in case not all
                // items belong to the same class
                $classes[get_class($item)][] = $item->getKey();
            }
        );

        // delete each item by class
        collect($classes)->each(
            function (array $ids, string $class) use ($query, $relation) {
                // select pivots matching each item for deletion
                $query->orWhere(
                    function (Builder $q) use ($class, $ids, $relation) {
                        $q->where($relation->getMorphType(), $class);
                        $q->whereIn(
                            $relation->getQualifiedForeignPivotKeyName(),
                            $ids
                        );
                    }
                );

                $class::whereIn((new $class)->getKeyName(), $ids)->delete();
            }
        );

        // delete pivots
        $query->delete();
    }
}
