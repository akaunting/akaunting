<?php

namespace App\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class Report implements Classifier
{
    public function name(): string
    {
        return 'Reports';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(\App\Abstracts\Report::class);
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
