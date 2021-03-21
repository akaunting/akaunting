<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Abstraction;

use Doctrine\DBAL\Driver\Result as DriverResult;
use Doctrine\DBAL\Exception;
use Traversable;

/**
 * Abstraction-level result statement execution result. Provides additional methods on top
 * of the driver-level interface.
 *
 * @deprecated
 */
interface Result extends DriverResult
{
    /**
     * Returns an iterator over the result set rows represented as numeric arrays.
     *
     * @return Traversable<int,array<int,mixed>>
     *
     * @throws Exception
     */
    public function iterateNumeric(): Traversable;

    /**
     * Returns an iterator over the result set rows represented as associative arrays.
     *
     * @return Traversable<int,array<string,mixed>>
     *
     * @throws Exception
     */
    public function iterateAssociative(): Traversable;

    /**
     * Returns an iterator over the values of the first column of the result set.
     *
     * @return Traversable<int,mixed>
     *
     * @throws Exception
     */
    public function iterateColumn(): Traversable;
}
