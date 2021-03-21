<?php

namespace Doctrine\DBAL\Driver\IBMDB2;

use Doctrine\DBAL\Driver\AbstractDB2Driver;

/**
 * IBM DB2 Driver.
 *
 * @deprecated Use {@link Driver} instead
 */
class DB2Driver extends AbstractDB2Driver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        $params['user']     = $username;
        $params['password'] = $password;
        $params['dbname']   = DataSourceName::fromConnectionParameters($params)->toString();

        return new Connection(
            $params,
            (string) $username,
            (string) $password,
            $driverOptions
        );
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     */
    public function getName()
    {
        return 'ibm_db2';
    }
}
