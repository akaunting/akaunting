<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver;

use Throwable;

/**
 * @psalm-immutable
 */
interface Exception extends Throwable
{
    /**
     * Returns the driver specific error code if available.
     *
     * @deprecated Use {@link getCode()} or {@link getSQLState()} instead
     *
     * Returns null if no driver specific error code is available
     * for the error raised by the driver.
     *
     * @return int|string|null
     */
    public function getErrorCode();

    /**
     * Returns the SQLSTATE the driver was in at the time the error occurred.
     *
     * Returns null if the driver does not provide a SQLSTATE for the error occurred.
     *
     * @return string|null
     */
    public function getSQLState();
}
