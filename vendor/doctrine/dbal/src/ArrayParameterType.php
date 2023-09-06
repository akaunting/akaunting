<?php

namespace Doctrine\DBAL;

final class ArrayParameterType
{
    /**
     * Represents an array of ints to be expanded by Doctrine SQL parsing.
     */
    public const INTEGER = ParameterType::INTEGER + Connection::ARRAY_PARAM_OFFSET;

    /**
     * Represents an array of strings to be expanded by Doctrine SQL parsing.
     */
    public const STRING = ParameterType::STRING + Connection::ARRAY_PARAM_OFFSET;

    /**
     * Represents an array of ascii strings to be expanded by Doctrine SQL parsing.
     */
    public const ASCII = ParameterType::ASCII + Connection::ARRAY_PARAM_OFFSET;

    /**
     * @internal
     *
     * @psalm-param self::INTEGER|self::STRING|self::ASCII $type
     *
     * @psalm-return ParameterType::INTEGER|ParameterType::STRING|ParameterType::ASCII
     */
    public static function toElementParameterType(int $type): int
    {
        return $type - Connection::ARRAY_PARAM_OFFSET;
    }

    private function __construct()
    {
    }
}
