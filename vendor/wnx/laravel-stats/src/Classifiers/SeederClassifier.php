<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Illuminate\Database\Seeder;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class SeederClassifier implements Classifier
{
    public function name(): string
    {
        return 'Seeders';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(Seeder::class);
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
