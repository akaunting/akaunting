<?php

namespace App\Traits;

use Plank\Mediable\Mediable;

/**
 * Mediable Trait.
 *
 * Provides functionality for attaching media to an eloquent model.
 *
 * @author Sean Fraser <sean@plankdesign.com>
 *
 * Whether the model should automatically reload its media relationship after modification.
 */
trait Media
{
    use Mediable;

    /**
     * Relationship for all attached media.
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function media()
    {
        $media = $this->morphToMany(config('mediable.model'), 'mediable')
            ->withPivot('tag', 'order')
            ->orderBy('order');

        // Skip deleted media if not detached
        if (config('mediable.detach_on_soft_delete') == false) {
            $media->whereNull('deleted_at');
        }

        return $media;
    }

    public function attachMedia($media, $tags): void
    {
        $tags = (array)$tags;
        $increments = $this->getOrderValueForTags($tags);

        $ids = $this->extractPrimaryIds($media);

        foreach ($tags as $tag) {
            $attach = [];
            foreach ($ids as $id) {
                $attach[$id] = [
                    'company_id' => company_id(),
                    'tag' => $tag,
                    'order' => ++$increments[$tag],
                ];
            }
            $this->media()->attach($attach);
        }

        $this->markMediaDirty($tags);
    }
}
