<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Config;
use function array_filter;
use function array_map;
use function array_values;
use function is_string;
use function preg_match;
use function trim;
use const PHP_VERSION_ID;

class SniffSettingsHelper
{

	/**
	 * @param string|int $settings
	 * @return int
	 */
	public static function normalizeInteger($settings): int
	{
		return (int) trim((string) $settings);
	}

	/**
	 * @param string[] $settings
	 * @return string[]
	 */
	public static function normalizeArray(array $settings): array
	{
		$settings = array_map(static function (string $value): string {
			return trim($value);
		}, $settings);
		$settings = array_filter($settings, static function (string $value): bool {
			return $value !== '';
		});
		return array_values($settings);
	}

	/**
	 * @param array<int|string, int|string> $settings
	 * @return array<int|string, int|string>
	 */
	public static function normalizeAssociativeArray(array $settings): array
	{
		$normalizedSettings = [];
		foreach ($settings as $key => $value) {
			if (is_string($key)) {
				$key = trim($key);
			}
			if (is_string($value)) {
				$value = trim($value);
			}
			if ($key === '' || $value === '') {
				continue;
			}
			$normalizedSettings[$key] = $value;
		}

		return $normalizedSettings;
	}

	public static function isValidRegularExpression(string $expression): bool
	{
		return preg_match('~^(?:\(.*\)|\{.*\}|\[.*\])[a-z]*\z~i', $expression) !== 0
			|| preg_match('~^([^a-z\s\\\\]).*\\1[a-z]*\z~i', $expression) !== 0;
	}

	public static function isEnabledByPhpVersion(?bool $value, int $phpVersionLimit): bool
	{
		if ($value !== null) {
			return $value;
		}

		$phpVersion = Config::getConfigData('php_version') !== null ? (int) Config::getConfigData('php_version') : PHP_VERSION_ID;
		return $phpVersion >= $phpVersionLimit;
	}

}
