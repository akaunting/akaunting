<?php

namespace Plank\Mediable\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Plank\Mediable\Exceptions\ImageManipulationException;
use Plank\Mediable\ImageManipulator;
use Plank\Mediable\Media;

class CreateImageVariants implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string[]
     */
    private $variantNames;
    /**
     * @var Collection|Media[]
     */
    private $models;

    /**
     * @var bool
     */
    private $forceRecreate;

    /**
     * CreateImageVariants constructor.
     * @param Media|Collection|Media[] $model
     * @param string|string[] $variantNames
     * @throws ImageManipulationException
     */
    public function __construct($models, $variantNames, bool $forceRecreate = false)
    {
        $models = $this->collect($models);
        $variantNames = (array) $variantNames;
        $this->validate($models, $variantNames);

        $this->variantNames = $variantNames;
        $this->models = $models;
        $this->forceRecreate = $forceRecreate;
    }

    public function handle()
    {
        foreach ($this->getModels() as $model) {
            foreach ($this->getVariantNames() as $variantName) {
                $this->getImageManipulator()->createImageVariant(
                    $model,
                    $variantName,
                    $this->getForceRecreate()
                );
            }
        }
    }

    /**
     * @return string[]
     */
    public function getVariantNames(): array
    {
        return $this->variantNames;
    }

    /**
     * @return Collection|Media[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    /**
     * @param Media $model
     * @param array $variantNames
     * @throws ImageManipulationException
     */
    private function validate(Collection $models, array $variantNames): void
    {
        $imageManipulator = $this->getImageManipulator();
        foreach ($models as $media) {
            $imageManipulator->validateMedia($media);
        }
        foreach ($variantNames as $variantName) {
            $imageManipulator->getVariantDefinition($variantName);
        }
    }

    private function getImageManipulator(): ImageManipulator
    {
        return app(ImageManipulator::class);
    }

    /**
     * @return bool
     */
    public function getForceRecreate(): bool
    {
        return $this->forceRecreate;
    }

    /**
     * @param Media|Collection|Media[] $models
     * @return bool
     */
    private function collect($models): Collection
    {
        if ($models instanceof Media) {
            $models = [$models];
        }
        return new Collection($models);
    }
}
