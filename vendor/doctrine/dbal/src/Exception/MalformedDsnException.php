<?php

namespace Doctrine\DBAL\Exception;

use InvalidArgumentException;

/** @psalm-immutable */
class MalformedDsnException extends InvalidArgumentException
{
    public static function new(): self
    {
        return new self('Malformed database connection URL');
    }
}
