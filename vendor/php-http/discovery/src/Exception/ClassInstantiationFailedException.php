<?php

namespace Http\Discovery\Exception;

use Http\Discovery\Exception;

/**
 * Thrown when a class fails to instantiate.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ClassInstantiationFailedException extends \RuntimeException implements Exception
{
}
