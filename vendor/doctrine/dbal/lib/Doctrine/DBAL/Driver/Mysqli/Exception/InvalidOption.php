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
final class InvalidOption extends MysqliException
{
    /**
     * @param mixed $option
     * @param mixed $value
     */
    public static function fromOption($option, $value): self
    {
        return new self(
            sprintf('Failed to set option %d with value "%s"', $option, $value)
        );
    }
}
