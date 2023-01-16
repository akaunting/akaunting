<?php

namespace App\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class Export implements Classifier
{
    public function name(): string
    {
        return 'Exports';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(\App\Abstracts\Export::class);
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
