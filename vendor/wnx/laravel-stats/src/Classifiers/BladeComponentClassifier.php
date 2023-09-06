<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;
use Illuminate\View\Component;

class BladeComponentClassifier implements Classifier
{
    public function name(): string
    {
        return 'Blade Components';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        if (! class_exists(Component::class)) {
            return false;
        }

        return $class->isSubclassOf(Component::class);
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
