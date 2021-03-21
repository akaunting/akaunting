<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use function strlen;
use function strpos;
use function substr;

class StringHelper
{

	public static function startsWith(string $haystack, string $needle): bool
	{
		return $needle === '' || strpos($haystack, $needle) === 0;
	}

	public static function endsWith(string $haystack, string $needle): bool
	{
		return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
	}

}
