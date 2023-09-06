<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceClassifier implements Classifier
{
    public function name(): string
    {
        return 'Resources';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        if ($class->isSubclassOf(JsonResource::class)) {
            return true;
        }
        return $class->isSubclassOf(ResourceCollection::class);
    }

    public function countsTowardsApplicationCode(): bool
    {
        return true;
    }

    public function countsTowardsTests(): bool
    {
        return false;
    }
}
