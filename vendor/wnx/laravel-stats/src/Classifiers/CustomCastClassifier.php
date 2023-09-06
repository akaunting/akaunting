<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Wnx\LaravelStats\Contracts\Classifier;
use Wnx\LaravelStats\ReflectionClass;

class CustomCastClassifier implements Classifier
{
    public function name(): string
    {
        return 'Custom Casts Components';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        if ((float) app()->version() < 7) {
            return false;
        }

        return $class->implementsInterface(CastsAttributes::class) ||
            $class->implementsInterface(CastsInboundAttributes::class);
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
