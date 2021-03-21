<?php

namespace Money\Exception;

use Money\Exception;

/**
 * Thrown when a string cannot be parsed to a Money object.
 *
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
final class ParserException extends \RuntimeException implements Exception
{
}
