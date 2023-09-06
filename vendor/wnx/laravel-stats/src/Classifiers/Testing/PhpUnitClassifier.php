<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Testing;

use PHPUnit\Framework\TestCase;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class PhpUnitClassifier implements Classifier
{
    public function name(): string
    {
        return 'PHPUnit Tests';
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
