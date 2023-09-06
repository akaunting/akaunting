<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Testing;

use Laravel\Dusk\TestCase;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class DuskClassifier implements Classifier
{
    public function name(): string
    {
        return 'DuskTests';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return class_exists(TestCase::class) && $class->isSubclassOf(TestCase::class);
    }

    public function countsTowardsApplicationCode(): bool
    {
        return false;
    }

    public function countsTowardsTests(): bool
    {
        return true;
    }
}
