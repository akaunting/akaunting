<?php

namespace Doctrine\DBAL\Driver\OCI8;

use Doctrine\DBAL\Driver\AbstractDriverException;

/**
 * @deprecated Use {@link Exception} instead
 *
 * @psalm-immutable
 */
class OCI8Exception extends AbstractDriverException
{
    /**
     * @param mixed[]|false $error
     *
     * @return OCI8Exception
     */
    public static function fromErrorInfo($error)
    {
        if ($error === false) {
            return new self('Database error occurred but no error information was retrieved from the driver.');
        }

        return new self($error['message'], null, $error['code']);
    }
}
