<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Illuminate\Support\ServiceProvider;
use Wnx\LaravelStats\Contracts\Classifier;

class ServiceProviderClassifier implements Classifier
{
    public function name(): string
    {
        return 'Service Providers';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(ServiceProvider::class);
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
