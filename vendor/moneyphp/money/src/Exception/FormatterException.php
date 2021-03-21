<?php

namespace Money\Exception;

use Money\Exception;

/**
 * Thrown when a Money object cannot be formatted into a string.
 *
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
final class FormatterException extends \RuntimeException implements Exception
{
}
