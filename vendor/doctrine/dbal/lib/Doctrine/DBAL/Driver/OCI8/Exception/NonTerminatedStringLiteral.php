<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver\OCI8\Exception;

use Doctrine\DBAL\Driver\OCI8\OCI8Exception;

use function sprintf;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class NonTerminatedStringLiteral extends OCI8Exception
{
    public static function new(int $offset): self
    {
        return new self(
            sprintf(
                'The statement contains non-terminated string literal starting at offset %d.',
                $offset
            )
        );
    }
}
