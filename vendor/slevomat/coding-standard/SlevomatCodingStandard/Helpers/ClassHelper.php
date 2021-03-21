<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function array_merge;
use function array_reverse;
use function sprintf;
use const T_ANON_CLASS;
use const T_FINAL;
use const T_STRING;
use const T_USE;

class ClassHelper
{

	public static function getClassPointer(File $phpcsFile, int $pointer): ?int
	{
		$classPointers = array_reverse(self::getAllClassPointers($phpcsFile));
		foreach ($classPointers as $classPointer) {
			if ($classPointer < $pointer && ScopeHelper::isInSameScope($phpcsFile, $classPointer, $pointer)) {
				return $classPointer;
			}
		}

		return null;
	}

	public static function isFinal(File $phpcsFile, int $classPointer): bool
	{
		return $phpcsFile->getTokens()[TokenHelper::findPreviousEffective($phpcsFile, $classPointer - 1)]['code'] === T_FINAL;
	}

	public static function getFullyQualifiedName(File $phpcsFile, int $classPointer): string
	{
		$className = self::getName($phpcsFile, $classPointer);

		$tokens = $phpcsFile->getTokens();
		if ($tokens[$classPointer]['code'] === T_ANON_CLASS) {
			return $className;
		}

		$name = sprintf('%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $className);
		$namespace = NamespaceHelper::findCurrentNamespaceName($phpcsFile, $classPointer);
		return $namespace !== null ? sprintf('%s%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $namespace, $name) : $name;
	}

	public static function getName(File $phpcsFile, int $classPointer): string
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$classPointer]['code'] === T_ANON_CLASS) {
			return 'class@anonymous';
		}

		return $tokens[TokenHelper::findNext($phpcsFile, T_STRING, $classPointer + 1, $tokens[$classPointer]['scope_opener'])]['content'];
	}

	/**
	 * @param File $phpcsFile
	 * @return array<int, string>
	 */
	public static function getAllNames(File $phpcsFile): array
	{
		$tokens = $phpcsFile->getTokens();

		$names = [];
		/** @var int $classPointer */
		foreach (self::getAllClassPointers($phpcsFile) as $classPointer) {
			if ($tokens[$classPointer]['code'] === T_ANON_CLASS) {
				continue;
			}

			$names[$classPointer] = self::getName($phpcsFile, $classPointer);
		}

		return $names;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $classPointer
	 * @return int[]
	 */
	public static function getTraitUsePointers(File $phpcsFile, int $classPointer): array
	{
		$useStatements = [];

		$tokens = $phpcsFile->getTokens();

		$scopeLevel = $tokens[$classPointer]['level'] + 1;
		for ($i = $tokens[$classPointer]['scope_opener'] + 1; $i < $tokens[$classPointer]['scope_closer']; $i++) {
			if ($tokens[$i]['code'] !== T_USE) {
				continue;
			}

			if ($tokens[$i]['level'] !== $scopeLevel) {
				continue;
			}

			$useStatements[] = $i;
		}

		return $useStatements;
	}

	/**
	 * @param File $phpcsFile
	 * @return array<int>
	 */
	private static function getAllClassPointers(File $phpcsFile): array
	{
		$lazyValue = static function () use ($phpcsFile): array {
			return TokenHelper::findNextAll($phpcsFile, array_merge(TokenHelper::$typeKeywordTokenCodes, [T_ANON_CLASS]), 0);
		};

		return SniffLocalCache::getAndSetIfNotCached($phpcsFile, 'classPointers', $lazyValue);
	}

}
