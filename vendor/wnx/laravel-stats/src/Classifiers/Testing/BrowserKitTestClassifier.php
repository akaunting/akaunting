<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers\Testing;

use Wnx\LaravelStats\ReflectionClass;
use Laravel\BrowserKitTesting\TestCase;
use Wnx\LaravelStats\Contracts\Classifier;

class BrowserKitTestClassifier implements Classifier
{
    public function name(): string
    {
        return 'BrowserKit Tests';
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
