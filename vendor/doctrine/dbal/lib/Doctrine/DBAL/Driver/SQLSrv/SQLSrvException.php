<?php

namespace Doctrine\DBAL\Driver\SQLSrv;

use Doctrine\DBAL\Driver\AbstractDriverException;
use Doctrine\DBAL\Driver\SQLSrv\Exception\Error;

/**
 * @deprecated Use {@link Exception} instead
 *
 * @psalm-immutable
 */
class SQLSrvException extends AbstractDriverException
{
    /**
     * Helper method to turn sql server errors into exception.
     *
     * @return SQLSrvException
     */
    public static function fromSqlSrvErrors()
    {
        return Error::new();
    }
}
