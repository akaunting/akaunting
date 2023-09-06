<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Nova;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class ResourceClassifier implements Classifier
{
    public function name(): string
    {
        return 'Nova Resources';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return class_exists(\Laravel\Nova\Resource::class) && $class->isSubclassOf(\Laravel\Nova\Resource::class);
    }

    public function countsTowardsApplicationCode(): bool
    {
        return false;
    }

    public function countsTowardsTests(): bool
    {
        return false;
    }
}
