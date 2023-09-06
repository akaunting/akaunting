<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Illuminate\Contracts\Auth\Access\Gate;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class PolicyClassifier implements Classifier
{
    public function name(): string
    {
        return 'Policies';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        $gate = app(Gate::class);

        return in_array(
            $class->getName(),
            $gate->policies()
        );
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
