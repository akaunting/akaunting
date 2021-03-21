<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver\OCI8\Exception;

use Doctrine\DBAL\Driver\OCI8\OCI8Exception;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class SequenceDoesNotExist extends OCI8Exception
{
    public static function new(): self
    {
        return new self('lastInsertId failed: Query was executed but no result was returned.');
    }
}
