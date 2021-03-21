<?php

namespace Doctrine\DBAL\Connections;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use InvalidArgumentException;

use function sprintf;
use function trigger_error;

use const E_USER_DEPRECATED;

/**
 * @deprecated Use PrimaryReadReplicaConnection instead
 */
class MasterSlaveConnection extends PrimaryReadReplicaConnection
{
    /**
     * Creates Primary Replica Connection.
     *
     * @internal The connection can be only instantiated by the driver manager.
     *
     * @param mixed[] $params
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        array $params,
        Driver $driver,
        ?Configuration $config = null,
        ?EventManager $eventManager = null
    ) {
        $this->deprecated(self::class, PrimaryReadReplicaConnection::class);

        if (isset($params['master'])) {
            $this->deprecated('Params key "master"', '"primary"');

            $params['primary'] = $params['master'];
        }

        if (isset($params['slaves'])) {
            $this->deprecated('Params key "slaves"', '"replica"');

            $params['replica'] = $params['slaves'];
        }

        if (isset($params['keepSlave'])) {
            $this->deprecated('Params key "keepSlave"', '"keepReplica"');

            $params['keepReplica'] = $params['keepSlave'];
        }

        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * Checks if the connection is currently towards the primary or not.
     */
    public function isConnectedToMaster(): bool
    {
        $this->deprecated('isConnectedtoMaster()', 'isConnectedToPrimary()');

        return $this->isConnectedToPrimary();
    }

    /**
     * @param string|null $connectionName
     *
     * @return bool
     */
    public function connect($connectionName = null)
    {
        if ($connectionName === 'master') {
            $connectionName = 'primary';

            $this->deprecated('connect("master")', 'ensureConnectedToPrimary()');
        }

        if ($connectionName === 'slave') {
            $connectionName = 'replica';

            $this->deprecated('connect("slave")', 'ensureConnectedToReplica()');
        }

        return $this->performConnect($connectionName);
    }

    private function deprecated(string $thing, string $instead): void
    {
        @trigger_error(
            sprintf(
                '%s is deprecated since doctrine/dbal 2.11 and will be removed in 3.0, use %s instead.',
                $thing,
                $instead
            ),
            E_USER_DEPRECATED
        );
    }
}
