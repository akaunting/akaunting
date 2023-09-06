<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;
use Illuminate\Foundation\Bus\Dispatchable;

class JobClassifier implements Classifier
{
    public function name(): string
    {
        return 'Jobs';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->usesTrait(Dispatchable::class);
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
