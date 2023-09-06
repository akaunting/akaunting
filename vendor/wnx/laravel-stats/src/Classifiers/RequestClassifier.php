<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;
use Illuminate\Foundation\Http\FormRequest;

class RequestClassifier implements Classifier
{
    public function name(): string
    {
        return 'Requests';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        if (! class_exists(FormRequest::class)) {
            return false;
        }

        return $class->isSubclassOf(FormRequest::class);
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
