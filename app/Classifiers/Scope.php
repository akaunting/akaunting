<?php

namespace App\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class Scope implements Classifier
{
    public function name(): string
    {
        return 'Scopes';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(\Illuminate\Database\Eloquent\Scope::class);
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
