<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Nova;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class DashboardClassifier implements Classifier
{
    public function name(): string
    {
        return 'Nova Dashboards';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return class_exists(\Laravel\Nova\Dashboard::class) && $class->isSubclassOf(\Laravel\Nova\Dashboard::class);
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
