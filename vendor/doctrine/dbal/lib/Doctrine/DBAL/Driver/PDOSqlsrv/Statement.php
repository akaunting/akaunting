<?php

namespace Doctrine\DBAL\Driver\PDOSqlsrv;

use Doctrine\DBAL\Driver\PDO;
use Doctrine\DBAL\ParameterType;

/**
 * PDO SQL Server Statement
 *
 * @deprecated Use {@link PDO\SQLSrv\Statement} instead.
 */
class Statement extends PDO\Statement
{
    /**
     * {@inheritdoc}
     */
    public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null, $driverOptions = null)
    {
        switch ($type) {
            case ParameterType::LARGE_OBJECT:
            case ParameterType::BINARY:
                if ($driverOptions === null) {
                    $driverOptions = \PDO::SQLSRV_ENCODING_BINARY;
                }

                break;

            case ParameterType::ASCII:
                $type          = ParameterType::STRING;
                $length        = 0;
                $driverOptions = \PDO::SQLSRV_ENCODING_SYSTEM;
                break;
        }

        return parent::bindParam($param, $variable, $type, $length, $driverOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        return $this->bindParam($param, $value, $type);
    }
}
