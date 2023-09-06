<?php declare(strict_types=1);

namespace Wnx\LaravelStats\RejectionStrategies;

use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\RejectionStrategy;

class RejectInternalClasses implements RejectionStrategy
{
    public function shouldClassBeRejected(ReflectionClass $class): bool
    {
        return $class->isInternal();
    }
}
