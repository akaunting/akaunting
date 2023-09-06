<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class NullClassifier implements Classifier
{
    public function name(): string
    {
        return 'Other';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return true;
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
