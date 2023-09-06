<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;
use Illuminate\Database\Migrations\Migration;

class MigrationClassifier implements Classifier
{
    public function name(): string
    {
        return 'Migrations';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(Migration::class);
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
