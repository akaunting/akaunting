<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Schema\Exception;

use Doctrine\DBAL\Schema\SchemaException;

use function sprintf;

/** @psalm-immutable */
final class NamespaceAlreadyExists extends SchemaException
{
    public static function new(string $namespaceName): self
    {
        return new self(
            sprintf('The namespace with name "%s" already exists.', $namespaceName),
            self::NAMESPACE_ALREADY_EXISTS,
        );
    }
}
