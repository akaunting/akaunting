<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Illuminate\Console\Command;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class CommandClassifier implements Classifier
{
    public function name(): string
    {
        return 'Commands';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(Command::class);
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
