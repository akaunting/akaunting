<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Illuminate\Contracts\Validation\Rule;
use Wnx\LaravelStats\Contracts\Classifier;

class RuleClassifier implements Classifier
{
    public function name(): string
    {
        return 'Rules';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->implementsInterface(Rule::class);
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
