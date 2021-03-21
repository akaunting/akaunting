<?php

namespace Doctrine\DBAL;

use function sprintf;

/**
 * Doctrine\DBAL\ConnectionException
 *
 * @psalm-immutable
 */
class SQLParserUtilsException extends Exception
{
    /**
     * @param string $paramName
     *
     * @return SQLParserUtilsException
     */
    public static function missingParam($paramName)
    {
        return new self(
            sprintf('Value for :%1$s not found in params array. Params array key should be "%1$s"', $paramName)
        );
    }

    /**
     * @param string $typeName
     *
     * @return SQLParserUtilsException
     */
    public static function missingType($typeName)
    {
        return new self(
            sprintf('Value for :%1$s not found in types array. Types array key should be "%1$s"', $typeName)
        );
    }
}
