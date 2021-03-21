<?php

namespace Doctrine\DBAL\Portability;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\ColumnCase;
use Doctrine\DBAL\Connection as BaseConnection;
use Doctrine\DBAL\Driver\PDO\Connection as PDOConnection;
use PDO;

use function func_get_args;

use const CASE_LOWER;
use const CASE_UPPER;

/**
 * Portability wrapper for a Connection.
 */
class Connection extends BaseConnection
{
    public const PORTABILITY_ALL           = 255;
    public const PORTABILITY_NONE          = 0;
    public const PORTABILITY_RTRIM         = 1;
    public const PORTABILITY_EMPTY_TO_NULL = 4;
    public const PORTABILITY_FIX_CASE      = 8;

    /**#@+
     *
     * @deprecated Will be removed as internal implementation details.
     */
    public const PORTABILITY_DB2          = 13;
    public const PORTABILITY_ORACLE       = 9;
    public const PORTABILITY_POSTGRESQL   = 13;
    public const PORTABILITY_SQLITE       = 13;
    public const PORTABILITY_OTHERVENDORS = 12;
    public const PORTABILITY_DRIZZLE      = 13;
    public const PORTABILITY_SQLANYWHERE  = 13;
    public const PORTABILITY_SQLSRV       = 13;
    /**#@-*/

    /** @var int */
    private $portability = self::PORTABILITY_NONE;

    /** @var int */
    private $case;

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        $ret = parent::connect();
        if ($ret) {
            $params = $this->getParams();
            if (isset($params['portability'])) {
                $this->portability = $params['portability'] = (new OptimizeFlags())(
                    $this->getDatabasePlatform(),
                    $params['portability']
                );
            }

            if (isset($params['fetch_case']) && $this->portability & self::PORTABILITY_FIX_CASE) {
                if ($this->_conn instanceof PDOConnection) {
                    // make use of c-level support for case handling
                    $this->_conn->setAttribute(PDO::ATTR_CASE, $params['fetch_case']);
                } else {
                    $this->case = $params['fetch_case'] === ColumnCase::LOWER ? CASE_LOWER : CASE_UPPER;
                }
            }
        }

        return $ret;
    }

    /**
     * @return int
     */
    public function getPortability()
    {
        return $this->portability;
    }

    /**
     * @return int
     */
    public function getFetchCase()
    {
        return $this->case;
    }

    /**
     * {@inheritdoc}
     */
    public function executeQuery($sql, array $params = [], $types = [], ?QueryCacheProfile $qcp = null)
    {
        $stmt = new Statement(parent::executeQuery($sql, $params, $types, $qcp), $this);
        $stmt->setFetchMode($this->defaultFetchMode);

        return $stmt;
    }

    /**
     * {@inheritdoc}
     *
     * @return Statement
     */
    public function prepare($sql)
    {
        $stmt = new Statement(parent::prepare($sql), $this);
        $stmt->setFetchMode($this->defaultFetchMode);

        return $stmt;
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $connection = $this->getWrappedConnection();

        $stmt = $connection->query(...func_get_args());
        $stmt = new Statement($stmt, $this);
        $stmt->setFetchMode($this->defaultFetchMode);

        return $stmt;
    }
}
