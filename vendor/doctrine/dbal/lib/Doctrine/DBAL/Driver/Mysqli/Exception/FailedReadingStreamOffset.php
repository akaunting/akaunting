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
final class FailedReadingStreamOffset extends MysqliException
{
    public static function new(int $offset): self
    {
        return new self(sprintf('Failed reading the stream resource for parameter offset %d.', $offset));
    }
}
