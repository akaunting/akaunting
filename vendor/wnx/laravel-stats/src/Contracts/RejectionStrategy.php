<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Contracts;

use Wnx\LaravelStats\ReflectionClass;

interface RejectionStrategy
{
    /**
     * Determine if the given Class should be rejected
     * A rejected Class does not count to the
     * project statistics.
     *
     *
     */
    public function shouldClassBeRejected(ReflectionClass $class): bool;
}
