<?php

namespace Doctrine\DBAL\Schema\Synchronizer;

use Doctrine\DBAL\Connection;
use Throwable;

/**
 * Abstract schema synchronizer with methods for executing batches of SQL.
 *
 * @deprecated
 */
abstract class AbstractSchemaSynchronizer implements SchemaSynchronizer
{
    /** @var Connection */
    protected $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param string[] $sql
     *
     * @return void
     */
    protected function processSqlSafely(array $sql)
    {
        foreach ($sql as $s) {
            try {
                $this->conn->exec($s);
            } catch (Throwable $e) {
            }
        }
    }

    /**
     * @param string[] $sql
     *
     * @return void
     */
    protected function processSql(array $sql)
    {
        foreach ($sql as $s) {
            $this->conn->exec($s);
        }
    }
}
