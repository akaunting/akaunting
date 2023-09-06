<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Nova;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class LensClassifier implements Classifier
{
    public function name(): string
    {
        return 'Nova Lenses';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return class_exists(\Laravel\Nova\Lenses\Lens::class) && $class->isSubclassOf(\Laravel\Nova\Lenses\Lens::class);
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
