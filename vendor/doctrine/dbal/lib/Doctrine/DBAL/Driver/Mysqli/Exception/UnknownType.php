<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver\Mysqli\Exception;

use Doctrine\DBAL\Driver\Mysqli\MysqliException;

use function sprintf;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class UnknownType extends MysqliException
{
    /**
     * @param mixed $type
     */
    public static function new($type): self
    {
        return new self(sprintf('Unknown type, %d given.', $type));
    }
}
