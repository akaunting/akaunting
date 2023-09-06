<?php

declare(strict_types=1);

namespace Http\Client\Common\Exception;

use Http\Client\Exception\HttpException;

/**
 * Redirect location cannot be chosen.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class MultipleRedirectionException extends HttpException
{
}
