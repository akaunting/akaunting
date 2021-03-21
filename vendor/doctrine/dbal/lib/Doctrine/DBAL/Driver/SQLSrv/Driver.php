<?php

namespace Doctrine\DBAL\Driver\SQLSrv;

use Doctrine\DBAL\Driver\AbstractSQLServerDriver;
use Doctrine\DBAL\Driver\AbstractSQLServerDriver\Exception\PortWithoutHost;

/**
 * Driver for ext/sqlsrv.
 */
class Driver extends AbstractSQLServerDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        $serverName = '';

        if (isset($params['host'])) {
            $serverName = $params['host'];

            if (isset($params['port'])) {
                $serverName .= ',' . $params['port'];
            }
        } elseif (isset($params['port'])) {
            throw PortWithoutHost::new();
        }

        if (isset($params['dbname'])) {
            $driverOptions['Database'] = $params['dbname'];
        }

        if (isset($params['charset'])) {
            $driverOptions['CharacterSet'] = $params['charset'];
        }

        if ($username !== null) {
            $driverOptions['UID'] = $username;
        }

        if ($password !== null) {
            $driverOptions['PWD'] = $password;
        }

        if (! isset($driverOptions['ReturnDatesAsStrings'])) {
            $driverOptions['ReturnDatesAsStrings'] = 1;
        }

        return new Connection($serverName, $driverOptions);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     */
    public function getName()
    {
        return 'sqlsrv';
    }
}
