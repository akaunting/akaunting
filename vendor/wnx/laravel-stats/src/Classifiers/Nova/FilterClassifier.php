<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Nova;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class FilterClassifier implements Classifier
{
    public function name(): string
    {
        return 'Nova Filters';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return class_exists(\Laravel\Nova\Filters\Filter::class) && $class->isSubclassOf(\Laravel\Nova\Filters\Filter::class);
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
