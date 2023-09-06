<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Nova;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class ActionClassifier implements Classifier
{
    public function name(): string
    {
        return 'Nova Actions';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return class_exists(\Laravel\Nova\Actions\Action::class) && $class->isSubclassOf(\Laravel\Nova\Actions\Action::class);
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
