<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use Wnx\LaravelStats\Contracts\Classifier;

class ModelClassifier implements Classifier
{
    public function name(): string
    {
        return 'Models';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(Model::class);
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
