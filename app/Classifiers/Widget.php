<?php

namespace App\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class Widget implements Classifier
{
    public function name(): string
    {
        return 'Widgets';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(\App\Abstracts\Widget::class);
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
