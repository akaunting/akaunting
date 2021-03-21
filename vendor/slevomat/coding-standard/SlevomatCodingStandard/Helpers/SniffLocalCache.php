<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use Closure;
use PHP_CodeSniffer\Files\File;
use function array_key_exists;
use function sprintf;

/**
 * @internal
 */
final class SniffLocalCache
{

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 * @var array<int, array<string, mixed>>
	 */
	private static $cache = [];

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 * @param File $phpcsFile
	 * @param string $key
	 * @param Closure $lazyValue
	 * @return mixed
	 */
	public static function getAndSetIfNotCached(File $phpcsFile, string $key, Closure $lazyValue)
	{
		$fixerLoops = $phpcsFile->fixer !== null ? $phpcsFile->fixer->loops : 0;
		$internalKey = sprintf('%s-%s', $phpcsFile->getFilename(), $key);

		self::setIfNotCached($fixerLoops, $internalKey, $lazyValue);

		return self::$cache[$fixerLoops][$internalKey] ?? null;
	}

	private static function setIfNotCached(int $fixerLoops, string $internalKey, Closure $lazyValue): void
	{
		if (array_key_exists($fixerLoops, self::$cache) && array_key_exists($internalKey, self::$cache[$fixerLoops])) {
			return;
		}

		self::$cache[$fixerLoops][$internalKey] = $lazyValue();

		if ($fixerLoops > 0) {
			unset(self::$cache[$fixerLoops - 1]);
		}
	}

}
