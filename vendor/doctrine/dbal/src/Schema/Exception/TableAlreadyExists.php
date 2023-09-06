<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Schema\Exception;

use Doctrine\DBAL\Schema\SchemaException;

use function sprintf;

/** @psalm-immutable */
final class TableAlreadyExists extends SchemaException
{
    public static function new(string $tableName): self
    {
        return new self(
            sprintf('The table with name "%s" already exists.', $tableName),
            self::TABLE_ALREADY_EXISTS,
        );
    }
}
