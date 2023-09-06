<?php

declare(strict_types=1);

namespace Money\Exception;

use Money\Exception;
use RuntimeException;

/**
 * Thrown when a string cannot be parsed to a Money object.
 */
final class ParserException extends RuntimeException implements Exception
{
}
