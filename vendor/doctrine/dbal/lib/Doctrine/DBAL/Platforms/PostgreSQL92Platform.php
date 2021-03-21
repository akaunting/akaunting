<?php

namespace Doctrine\DBAL\Platforms;

use Doctrine\DBAL\Types\Types;

use function sprintf;

/**
 * Provides the behavior, features and SQL dialect of the PostgreSQL 9.2 database platform.
 *
 * @deprecated Use PostgreSQL 9.4 or newer
 */
class PostgreSQL92Platform extends PostgreSQL91Platform
{
    /**
     * {@inheritdoc}
     */
    public function getJsonTypeDeclarationSQL(array $column)
    {
        return 'JSON';
    }

    /**
     * {@inheritdoc}
     */
    public function getSmallIntTypeDeclarationSQL(array $column)
    {
        if (! empty($column['autoincrement'])) {
            return 'SMALLSERIAL';
        }

        return parent::getSmallIntTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    public function hasNativeJsonType()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getReservedKeywordsClass()
    {
        return Keywords\PostgreSQL92Keywords::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeDoctrineTypeMappings()
    {
        parent::initializeDoctrineTypeMappings();

        $this->doctrineTypeMapping['json'] = Types::JSON;
    }

    /**
     * {@inheritdoc}
     */
    public function getCloseActiveDatabaseConnectionsSQL($database)
    {
        return sprintf(
            'SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = %s',
            $this->quoteStringLiteral($database)
        );
    }
}
