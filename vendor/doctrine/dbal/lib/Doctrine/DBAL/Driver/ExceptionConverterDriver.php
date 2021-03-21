<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Driver\DriverException as TheDriverException;
use Doctrine\DBAL\Exception\DriverException;

/**
 * Contract for a driver that is capable of converting DBAL driver exceptions into standardized DBAL driver exceptions.
 *
 * @deprecated
 */
interface ExceptionConverterDriver
{
    /**
     * Converts a given DBAL driver exception into a standardized DBAL driver exception.
     *
     * It evaluates the vendor specific error code and SQLSTATE and transforms
     * it into a unified {@link DriverException} subclass.
     *
     * @deprecated
     *
     * @param string             $message   The DBAL exception message to use.
     * @param TheDriverException $exception The DBAL driver exception to convert.
     *
     * @return DriverException An instance of one of the DriverException subclasses.
     */
    public function convertException($message, TheDriverException $exception);
}
