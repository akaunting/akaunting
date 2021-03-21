<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver\PDO;

use Doctrine\DBAL\Driver\PDOException;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class Exception extends PDOException
{
    public static function new(\PDOException $exception): self
    {
        return new self($exception);
    }
}
