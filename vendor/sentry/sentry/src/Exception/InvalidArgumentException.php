<?php

declare(strict_types=1);

namespace Sentry\Exception;

/**
 * This class represents an exception thrown if an argument does not match with
 * the expected value.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 *
 * @deprecated since version 3.1, to be removed in 4.0
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}
