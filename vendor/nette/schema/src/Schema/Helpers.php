<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Schema;

use Nette;
use Nette\Utils\Reflection;


/**
 * @internal
 */
final class Helpers
{
	use Nette\StaticClass;

	public const PreventMerging = '_prevent_merging';
	public const PREVENT_MERGING = self::PreventMerging;


	/**
	 * Merges dataset. Left has higher priority than right one.
	 * @return array|string
	 */
	public static function merge($value, $base)
	{
		if (is_array($value) && isset($value[self::PreventMerging])) {
			unset($value[self::PreventMerging]);
			return $value;
		}

		if (is_array($value) && is_array($base)) {
			$index = 0;
			foreach ($value as $key => $val) {
				if ($key === $index) {
					$base[] = $val;
					$index++;
				} else {
					$base[$key] = static::merge($val, $base[$key] ?? null);
				}
			}

			return $base;

		} elseif ($value === null && is_array($base)) {
			return $base;

		} else {
			return $value;
		}
	}


	public static function getPropertyType(\ReflectionProperty $prop): ?string
	{
		if (!class_exists(Nette\Utils\Type::class)) {
			throw new Nette\NotSupportedException('Expect::from() requires nette/utils 3.x');
		} elseif ($type = Nette\Utils\Type::fromReflection($prop)) {
			return (string) $type;
		} elseif ($type = preg_replace('#\s.*#', '', (string) self::parseAnnotation($prop, 'var'))) {
			$class = Reflection::getPropertyDeclaringClass($prop);
			return preg_replace_callback('#[\w\\\\]+#', function ($m) use ($class) {
				return Reflection::expandClassName($m[0], $class);
			}, $type);
		}

		return null;
	}


	/**
	 * Returns an annotation value.
	 * @param  \ReflectionProperty  $ref
	 */
	public static function parseAnnotation(\Reflector $ref, string $name): ?string
	{
		if (!Reflection::areCommentsAvailable()) {
			throw new Nette\InvalidStateException('You have to enable phpDoc comments in opcode cache.');
		}

		$re = '#[\s*]@' . preg_quote($name, '#') . '(?=\s|$)(?:[ \t]+([^@\s]\S*))?#';
		if ($ref->getDocComment() && preg_match($re, trim($ref->getDocComment(), '/*'), $m)) {
			return $m[1] ?? '';
		}

		return null;
	}


	/**
	 * @param  mixed  $value
	 */
	public static function formatValue($value): string
	{
		if (is_object($value)) {
			return 'object ' . get_class($value);
		} elseif (is_string($value)) {
			return "'" . Nette\Utils\Strings::truncate($value, 15, '...') . "'";
		} elseif (is_scalar($value)) {
			return var_export($value, true);
		} else {
			return strtolower(gettype($value));
		}
	}
}
