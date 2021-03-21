<?php

namespace Doctrine\DBAL\Sharding;

use Doctrine\DBAL\Exception;

/**
 * Sharding related Exceptions
 *
 * @deprecated
 *
 * @psalm-immutable
 */
class ShardingException extends Exception
{
    /**
     * @return ShardingException
     */
    public static function notImplemented()
    {
        return new self('This functionality is not implemented with this sharding provider.', 1331557937);
    }

    /**
     * @return ShardingException
     */
    public static function missingDefaultFederationName()
    {
        return new self('SQLAzure requires a federation name to be set during sharding configuration.', 1332141280);
    }

    /**
     * @return ShardingException
     */
    public static function missingDefaultDistributionKey()
    {
        return new self('SQLAzure requires a distribution key to be set during sharding configuration.', 1332141329);
    }

    /**
     * @return ShardingException
     */
    public static function activeTransaction()
    {
        return new self('Cannot switch shard during an active transaction.', 1332141766);
    }

    /**
     * @return ShardingException
     */
    public static function noShardDistributionValue()
    {
        return new self('You have to specify a string or integer as shard distribution value.', 1332142103);
    }

    /**
     * @return ShardingException
     */
    public static function missingDistributionType()
    {
        return new self("You have to specify a sharding distribution type such as 'integer', 'string', 'guid'.");
    }
}
