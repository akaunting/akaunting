<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver\SQLSrv\Exception;

use Doctrine\DBAL\Driver\SQLSrv\SQLSrvException;

use function rtrim;
use function sqlsrv_errors;

use const SQLSRV_ERR_ERRORS;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class Error extends SQLSrvException
{
    public static function new(): self
    {
        $message   = '';
        $sqlState  = null;
        $errorCode = null;

        foreach ((array) sqlsrv_errors(SQLSRV_ERR_ERRORS) as $error) {
            $message .= 'SQLSTATE [' . $error['SQLSTATE'] . ', ' . $error['code'] . ']: ' . $error['message'] . "\n";

            if ($sqlState === null) {
                $sqlState = $error['SQLSTATE'];
            }

            if ($errorCode !== null) {
                continue;
            }

            $errorCode = $error['code'];
        }

        if (! $message) {
            $message = 'SQL Server error occurred but no error message was retrieved from driver.';
        }

        return new self(rtrim($message), $sqlState, $errorCode);
    }
}
