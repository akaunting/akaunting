<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wnx\LaravelStats\Contracts\Classifier;
use Wnx\LaravelStats\ReflectionClass;

class DatabaseFactoryClassifier implements Classifier
{
    public function name(): string
    {
        return 'Database Factories';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        if ((float) app()->version() < 8) {
            return false;
        }

        return $class->isSubclassOf(Factory::class);
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
