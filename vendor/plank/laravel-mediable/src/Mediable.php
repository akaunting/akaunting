<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Mediable Trait.
 *
 * Provides functionality for attaching media to an eloquent model.
 * Whether the model should automatically reload its media relationship after modification.
 *
 * @property MediableCollection $media
 * @property Pivot $pivot
 * @method static Builder withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static Builder withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static Builder withMediaMatchAll($tags = [], bool $withVariants = false)
 * @method static Builder withMediaAndVariantsMatchAll($tags = [])
 * @method static Builder whereHasMedia($tags = [], bool $matchAll = false)
 * @method static Builder whereHasMediaMatchAll($tags)
 *
 */
trait Mediable
{
    /**
     * List of media tags that have been modified since last load.
     * @var string[]
     */
    private $mediaDirtyTags = [];

    /**
     * Boot the Mediable trait.
     *
     * @return void
     */
    public static function bootMediable(): void
    {
        static::deleted(function (self $model) {
            $model->handleMediableDeletion();
        });
    }

    /**
     * Relationship for all attached media.
     * @return MorphToMany
     */
    public function media(): MorphToMany
    {
        return $this
            ->morphToMany(
                config('mediable.model'),
                'mediable',
                config('mediable.mediables_table', 'mediables'),
                'mediable_id',
                config('mediable.mediables_table_related_key', 'media_id')
            )
            ->withPivot('tag', 'order')
            ->orderBy('order');
    }

    /**
     * Query scope to detect the presence of one or more attached media for a given tag.
     * @param  Builder $q
     * @param  string|string[] $tags
     * @param  bool $matchAll
     * @return void
     */
    public function scopeWhereHasMedia(Builder $q, $tags = [], bool $matchAll = false): void
    {
        $tags = (array)$tags;
        if ($matchAll && count($tags) > 1) {
            $grammar = $q->getQuery()->getGrammar();
            $subquery = $this->newMatchAllQuery($tags)
                ->selectRaw('count(*)')
                ->whereRaw(
                    $grammar->wrap($this->media()->getQualifiedForeignPivotKeyName())
                    . ' = ' . $grammar->wrap($this->getQualifiedKeyName())
                );
            $q->whereRaw('(' . $subquery->toSql() . ') >= 1', $subquery->getBindings());

            return;
        }

        $q->whereHas('media', function (Builder $q) use ($tags) {
            if (count($tags) > 0) {
                $q->whereIn('tag', $tags);
            }
        });
    }

    /**
     * Query scope to detect the presence of one or more attached media that is bound to
     * all of the specified tags simultaneously.
     * @param  Builder $q
     * @param  string|string[] $tags
     * @return void
     */
    public function scopeWhereHasMediaMatchAll(Builder $q, array $tags): void
    {
        $this->scopeWhereHasMedia($q, $tags, true);
    }

    /**
     * Query scope to eager load attached media.
     *
     * @param Builder|Mediable $q
     * @param string|string[] $tags If one or more tags are specified, only media attached to those tags will be loaded.
     * @param bool $matchAll Only load media matching all provided tags
     * @param bool $withVariants If true, also load the variants and/or originalMedia relation of each Media
     * @return void
     */
    public function scopeWithMedia(
        Builder $q,
        $tags = [],
        bool $matchAll = false,
        bool $withVariants = false
    ): void {
        $tags = (array)$tags;

        if (empty($tags)) {
            if ($withVariants) {
                $q->with(
                    'media.variants',
                    'media.originalMedia.variants'
                );
            } else {
                $q->with('media');
            }

            return;
        }

        if ($matchAll) {
            $q->with(
                [
                    'media' => function (MorphToMany $q) use ($tags, $withVariants) {
                        $this->addMatchAllToEagerLoadQuery($q, $tags);
                        if ($withVariants) {
                            $q->with(['variants', 'originalMedia.variants']);
                        }
                    }
                ]
            );
            return;
        }

        $q->with(
            [
                'media' => function (MorphToMany $q) use ($tags, $withVariants) {
                    $q->wherePivotIn('tag', $tags);

                    if ($withVariants) {
                        $q->with(['variants', 'originalMedia.variants']);
                    }
                }
            ]
        );
    }

    /**
     * Query scope to eager load attached media, as well as their variants
     * of those media.
     * @param Builder $q
     * @param array $tags
     * @param bool $matchAll
     */
    public function scopeWithMediaAndVariants(
        Builder $q,
        $tags = [],
        bool $matchAll = false
    ) {
        $this->scopeWithMedia($q, $tags, $matchAll, true);
    }

    /**
     * Query scope to eager load attached media assigned to multiple tags.
     * @param  Builder $q
     * @param  string|string[] $tags
     * @param bool $withVariants If true, also load the variants and/or originalMedia relation of each Media
     * @return void
     */
    public function scopeWithMediaMatchAll(
        Builder $q,
        $tags = [],
        bool $withVariants = false
    ): void {
        $tags = (array)$tags;
        $this->scopeWithMedia($q, $tags, true, $withVariants);
    }

    /**
     * Query scope to eager load attached media assigned to multiple tags, as well as the variants of those media.
     * @param Builder $q
     * @param array $tags
     */
    public function scopeWithMediaAndVariantsMatchAll(Builder $q, $tags = []): void
    {
        $this->scopeWithMedia($q, $tags, true, true);
    }

    /**
     * Lazy eager load attached media.
     * @param string|string[] $tags If one or more tags are specified, only media attached to those tags will be loaded.
     * @param bool $matchAll Only load media matching all provided tags
     * @param bool $withVariants If true, also load the variants and/or originalMedia relation of each Media
     * @return $this
     */
    public function loadMedia(
        $tags = [],
        bool $matchAll = false,
        bool $withVariants = false
    ): self {
        $tags = (array)$tags;

        if (empty($tags)) {
            if ($withVariants) {
                return $this->load(['media.originalMedia.variants', 'media.variants']);
            } else {
                return $this->load('media');
            }
        }

        if ($matchAll) {
            return $this->load(
                [
                    'media' => function (MorphToMany $q) use ($tags, $withVariants) {
                        $this->addMatchAllToEagerLoadQuery($q, $tags);
                        if ($withVariants) {
                            $q->with(['originalMedia.variants', 'variants']);
                        }
                    }
                ]
            );
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

    /** Lazy eager load attached media, as well as their variants.
     * @param array $tags
     * @param bool $matchAll
     * @return $this
     */
    public function loadMediaWithVariants($tags = [], bool $matchAll = false): self
    {
        return $this->loadMedia($tags, $matchAll, true);
    }

    /**
     * Lazy eager load attached media relationships matching all provided tags.
     * @param  string|string[] $tags one or more tags
     * @param bool $withVariants If true, also load the variants and/or originalMedia relation of each Media
     * @return $this
     */
    public function loadMediaMatchAll($tags = [], bool $withVariants = false): self
    {
        return $this->loadMedia($tags, true, $withVariants);
    }

    /**
     * Lazy eager load attached media relationships matching all provided tags, as well
     * as the variants of those media.
     * @param array $tags
     * @return $this
     */
    public function loadMediaWithVariantsMatchAll($tags = []): self
    {
        return $this->loadMedia($tags, true, true);
    }

    /**
     * Attach a media entity to the model with one or more tags.
     * @param string|int|int[]|Media|Collection $media Either a string or numeric id, an array of ids, an instance of `Media` or an instance of `Collection`
     * @param string|string[] $tags One or more tags to define the relation
     * @return void
     */
    public function attachMedia($media, $tags): void
    {
        $tags = (array)$tags;
        $increments = $this->getOrderValueForTags($tags);

        $ids = $this->extractPrimaryIds($media);

        foreach ($tags as $tag) {
            $attach = [];
            foreach ($ids as $id) {
                $attach[$id] = [
                    'tag' => $tag,
                    'order' => ++$increments[$tag],
                ];
            }
            $this->media()->attach($attach);
        }

        $this->markMediaDirty($tags);
    }

    /**
     * Replace the existing media collection for the specified tag(s).
     * @param string|int|int[]|Media|Collection $media
     * @param string|string[] $tags
     * @return void
     */
    public function syncMedia($media, $tags): void
    {
        $this->detachMediaTags($tags);
        $this->attachMedia($media, $tags);
    }

    /**
     * Detach a media item from the model.
     * @param  string|int|Media|Collection $media
     * @param  string|string[]|null $tags
     * If provided, will remove the media from the model for the provided tag(s) only
     * If omitted, will remove the media from the media for all tags
     * @return void
     */
    public function detachMedia($media, $tags = null): void
    {
        $query = $this->media();
        if ($tags) {
            $query->wherePivotIn('tag', (array)$tags);
        }
        $query->detach($media);
        $this->markMediaDirty($tags);
    }

    /**
     * Remove one or more tags from the model, detaching any media using those tags.
     * @param  string|string[] $tags
     * @return void
     */
    public function detachMediaTags($tags): void
    {
        $this->media()->newPivotStatement()
            ->where($this->media()->getMorphType(), $this->media()->getMorphClass())
            ->where($this->media()->getQualifiedForeignPivotKeyName(), $this->getKey())
            ->whereIn('tag', (array)$tags)->delete();
        $this->markMediaDirty($tags);
    }

    /**
     * Check if the model has any media attached to one or more tags.
     * @param  string|string[] $tags
     * @param  bool $matchAll
     * If false, will return true if the model has any attach media for any of the provided tags
     * If true, will return true is the model has any media that are attached to all of provided tags simultaneously
     * @return bool
     */
    public function hasMedia($tags, bool $matchAll = false): bool
    {
        return count($this->getMedia($tags, $matchAll)) > 0;
    }

    /**
     * Retrieve media attached to the model.
     * @param  string|string[] $tags
     * @param  bool $matchAll
     * If false, will return media attached to any of the provided tags
     * If true, will return media attached to all of the provided tags simultaneously
     * @return Collection|Media[]
     */
    public function getMedia($tags, bool $matchAll = false): Collection
    {
        if ($matchAll) {
            return $this->getMediaMatchAll($tags);
        }

        $this->rehydrateMediaIfNecessary($tags);

        return $this->media
            //exclude media not matching at least one tag
            ->filter(function (Media $media) use ($tags) {
                return in_array($media->pivot->tag, (array)$tags);
            })->keyBy(function (Media $media) {
                return $media->getKey();
            })->values();
    }

    /**
     * Retrieve media attached to multiple tags simultaneously.
     * @param string[] $tags
     * @return Collection|Media[]
     */
    public function getMediaMatchAll(array $tags): Collection
    {
        $this->rehydrateMediaIfNecessary($tags);

        //group all tags for each media
        $modelTags = $this->media->reduce(function ($carry, Media $media) {
            $carry[$media->getKey()][] = $media->pivot->tag;

            return $carry;
        }, []);

        //exclude media not matching all tags
        return $this->media->filter(function (Media $media) use ($tags, $modelTags) {
            return count(array_intersect($tags, $modelTags[$media->getKey()])) === count($tags);
        })->keyBy(function (Media $media) {
            return $media->getKey();
        })->values();
    }

    /**
     * Shorthand for retrieving the first attached media item.
     * @param  string|string[] $tags
     * @param  bool $matchAll
     * @see \Plank\Mediable\Mediable::getMedia()
     * @return Media|null
     */
    public function firstMedia($tags, bool $matchAll = false): ?Media
    {
        return $this->getMedia($tags, $matchAll)->first();
    }

    /**
     * Shorthand for retrieving the last attached media item.
     * @param  string|string[] $tags
     * @param  bool $matchAll
     * @see \Plank\Mediable\Mediable::getMedia()
     * @return Media|null
     */
    public function lastMedia($tags, $matchAll = false): ?Media
    {
        return $this->getMedia($tags, $matchAll)->last();
    }

    /**
     * Retrieve all media grouped by tag name.
     * @return Collection|Media[]
     */
    public function getAllMediaByTag(): Collection
    {
        $this->rehydrateMediaIfNecessary();

        return $this->media->groupBy('pivot.tag');
    }

    /**
     * Get a list of all tags that the media is attached to.
     * @param  Media $media
     * @return string[]
     */
    public function getTagsForMedia(Media $media): array
    {
        $this->rehydrateMediaIfNecessary();

        return $this->media->reduce(function ($carry, Media $item) use ($media) {
            if ($item->getKey() === $media->getKey()) {
                $carry[] = $item->pivot->tag;
            }

            return $carry;
        }, []);
    }

    /**
     * Indicate that the media attached to the provided tags has been modified.
     * @param  string|string[] $tags
     * @return void
     */
    protected function markMediaDirty($tags): void
    {
        foreach ((array)$tags as $tag) {
            $this->mediaDirtyTags[$tag] = $tag;
        }
    }

    /**
     * Check if media attached to the specified tags has been modified.
     * @param  null|string|string[] $tags
     * If omitted, will return `true` if any tags have been modified
     * @return bool
     */
    protected function mediaIsDirty($tags = null): bool
    {
        if (is_null($tags)) {
            return count($this->mediaDirtyTags) > 0;
        } else {
            return count(array_intersect((array)$tags, $this->mediaDirtyTags)) > 0;
        }
    }

    /**
     * Reloads media relationship if allowed and necessary.
     * @param  null|string|string[] $tags
     * @return void
     */
    protected function rehydrateMediaIfNecessary($tags = null): void
    {
        if ($this->rehydratesMedia() && $this->mediaIsDirty($tags)) {
            $this->loadMedia();
        }
    }

    /**
     * Check whether the model is allowed to automatically reload media relationship.
     *
     * Can be overridden by setting protected property `$rehydrates_media` on the model.
     * @return bool
     */
    protected function rehydratesMedia(): bool
    {
        if (property_exists($this, 'rehydrates_media')) {
            return $this->rehydrates_media;
        }

        return (bool)config('mediable.rehydrate_media', true);
    }

    /**
     * Generate a query builder for.
     * @param  string|string[] $tags
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newMatchAllQuery($tags = []): \Illuminate\Database\Query\Builder
    {
        $tags = (array)$tags;
        $grammar = $this->media()->getBaseQuery()->getGrammar();
        return $this->media()->newPivotStatement()
            ->where($this->media()->getMorphType(), $this->media()->getMorphClass())
            ->whereIn('tag', $tags)
            ->groupBy($this->media()->getQualifiedRelatedPivotKeyName())
            ->havingRaw(
                'count(' . $grammar->wrap($this->media()->getQualifiedRelatedPivotKeyName()) . ') = ' . count($tags)
            );
    }

    /**
     * Modify an eager load query to only load media assigned to all provided tags simultaneously.
     * @param  MorphToMany $q
     * @param  string|string[] $tags
     * @return void
     */
    protected function addMatchAllToEagerLoadQuery(MorphToMany $q, $tags = []): void
    {
        $tags = (array)$tags;
        $grammar = $q->getBaseQuery()->getGrammar();
        $subquery = $this->newMatchAllQuery($tags)->select($this->media()->getQualifiedRelatedPivotKeyName());
        $q->whereRaw(
            $grammar->wrap($this->media()->getQualifiedRelatedPivotKeyName()) . ' IN (' . $subquery->toSql() . ')',
            $subquery->getBindings()
        );
        $q->wherePivotIn('tag', $tags);
    }

    /**
     * Determine whether media relationships should be detached when the model is deleted or soft deleted.
     * @return void
     */
    protected function handleMediableDeletion(): void
    {
        // only cascade soft deletes when configured
        if (static::hasGlobalScope(SoftDeletingScope::class) && !$this->forceDeleting) {
            if (config('mediable.detach_on_soft_delete')) {
                $this->media()->detach();
            }
        // always cascade for hard deletes
        } else {
            $this->media()->detach();
        }
    }

    /**
     * Determine the highest order value assigned to each provided tag.
     * @param  string|string[] $tags
     * @return int[]
     */
    private function getOrderValueForTags($tags): array
    {
        $q = $this->media()->newPivotStatement();
        $tags = array_map('strval', (array)$tags);
        $grammar = $q->getGrammar();

        $result = $q->selectRaw($grammar->wrap('tag') . ', max(' . $grammar->wrap('order') . ') as aggregate')
            ->where('mediable_type', $this->getMorphClass())
            ->where('mediable_id', $this->getKey())
            ->whereIn('tag', $tags)
            ->groupBy('tag')
            ->pluck('aggregate', 'tag');

        $empty = array_combine($tags, array_fill(0, count($tags), 0));

        $merged = collect($result)->toArray() + $empty;
        return $merged;
    }

    /**
     * Convert mixed input to array of ids.
     * @param  mixed $input
     * @return int[]|string[]
     */
    private function extractPrimaryIds($input): array
    {
        if ($input instanceof Collection) {
            return $input->modelKeys();
        }

        if ($input instanceof Media) {
            return [$input->getKey()];
        }

        return (array)$input;
    }

    /**
     * {@inheritdoc}
     */
    public function load($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        if (array_key_exists('media', $relations)
            || in_array('media', $relations)
        ) {
            $this->mediaDirtyTags = [];
        }

        return parent::load($relations);
    }

    /**
     * {@inheritdoc}
     * @return MediableCollection
     */
    public function newCollection(array $models = [])
    {
        return new MediableCollection($models);
    }
}
