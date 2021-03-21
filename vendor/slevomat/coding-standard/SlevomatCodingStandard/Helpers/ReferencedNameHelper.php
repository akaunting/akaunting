<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use function array_merge;
use function array_reverse;
use function array_values;
use function count;
use function in_array;
use const T_ANON_CLASS;
use const T_ARRAY;
use const T_AS;
use const T_BITWISE_AND;
use const T_BITWISE_OR;
use const T_CATCH;
use const T_CLASS;
use const T_COLON;
use const T_COMMA;
use const T_CONST;
use const T_DECLARE;
use const T_DOUBLE_COLON;
use const T_ELLIPSIS;
use const T_EXTENDS;
use const T_FUNCTION;
use const T_GOTO;
use const T_IMPLEMENTS;
use const T_INSTANCEOF;
use const T_NAMESPACE;
use const T_NEW;
use const T_NULLABLE;
use const T_OBJECT_OPERATOR;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;
use const T_TRAIT;
use const T_USE;
use const T_VARIABLE;

/**
 * Following type name occurrences are considered as a referenced name:
 *
 * - extending a class, implementing an interface
 * - typehinting a class or an interface
 * - creating new instance of a class
 * - class whose static method or a property is accessed
 * - thrown and caught exception names
 *
 * Following occurrences are not considered as a referenced name:
 *
 * - namespace name
 * - type name in a use statement
 * - class name in a class definition
 * - method name alias imported from trait
 */
class ReferencedNameHelper
{

	/**
	 * @param File $phpcsFile
	 * @param int $openTagPointer
	 * @return ReferencedName[] referenced names
	 */
	public static function getAllReferencedNames(File $phpcsFile, int $openTagPointer): array
	{
		$lazyValue = static function () use ($phpcsFile, $openTagPointer): array {
			return self::createAllReferencedNames($phpcsFile, $openTagPointer);
		};

		return SniffLocalCache::getAndSetIfNotCached($phpcsFile, 'references', $lazyValue);
	}

	public static function getReferenceName(File $phpcsFile, int $nameStartPointer, int $nameEndPointer): string
	{
		$tokens = $phpcsFile->getTokens();

		$referencedName = '';
		for ($i = $nameStartPointer; $i <= $nameEndPointer; $i++) {
			if (in_array($tokens[$i]['code'], Tokens::$emptyTokens, true)) {
				continue;
			}

			$referencedName .= $tokens[$i]['content'];
		}

		return $referencedName;
	}

	public static function getReferencedNameEndPointer(File $phpcsFile, int $startPointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$nameTokenCodes = TokenHelper::getNameTokenCodes();

		$nameTokenCodesWithWhitespace = array_merge($nameTokenCodes, Tokens::$emptyTokens);

		$lastNamePointer = $startPointer;
		for ($i = $startPointer + 1; $i < count($tokens); $i++) {
			if (!in_array($tokens[$i]['code'], $nameTokenCodesWithWhitespace, true)) {
				break;
			}

			if (!in_array($tokens[$i]['code'], $nameTokenCodes, true)) {
				continue;
			}

			$lastNamePointer = $i;
		}

		return $lastNamePointer;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $openTagPointer
	 * @return ReferencedName[] referenced names
	 */
	private static function createAllReferencedNames(File $phpcsFile, int $openTagPointer): array
	{
		$tokens = $phpcsFile->getTokens();

		$beginSearchAtPointer = $openTagPointer + 1;
		$types = [];

		$nameTokenCodes = TokenHelper::getNameTokenCodes();

		while (true) {
			$nameStartPointer = TokenHelper::findNext($phpcsFile, $nameTokenCodes, $beginSearchAtPointer);
			if ($nameStartPointer === null) {
				break;
			}

			if (!self::isReferencedName($phpcsFile, $nameStartPointer)) {
				$beginSearchAtPointer = TokenHelper::findNextExcluding(
					$phpcsFile,
					array_merge(TokenHelper::$ineffectiveTokenCodes, $nameTokenCodes),
					$nameStartPointer + 1
				);
				continue;
			}

			$nameEndPointer = self::getReferencedNameEndPointer($phpcsFile, $nameStartPointer);

			$nextTokenAfterEndPointer = TokenHelper::findNextEffective($phpcsFile, $nameEndPointer + 1);
			$previousTokenBeforeStartPointer = TokenHelper::findPreviousEffective($phpcsFile, $nameStartPointer - 1);
			$type = ReferencedName::TYPE_DEFAULT;
			if ($nextTokenAfterEndPointer !== null && $previousTokenBeforeStartPointer !== null) {
				if ($tokens[$nextTokenAfterEndPointer]['code'] === T_OPEN_PARENTHESIS) {
					if ($tokens[$previousTokenBeforeStartPointer]['code'] !== T_NEW) {
						$type = ReferencedName::TYPE_FUNCTION;
					}
				} elseif ($tokens[$nextTokenAfterEndPointer]['code'] === T_BITWISE_AND) {
					$tokenAfterNextToken = TokenHelper::findNextEffective($phpcsFile, $nextTokenAfterEndPointer + 1);
					if (!in_array($tokens[$tokenAfterNextToken]['code'], [T_VARIABLE, T_ELLIPSIS], true)) {
						$type = ReferencedName::TYPE_CONSTANT;
					}
				} elseif (
					!in_array($tokens[$nextTokenAfterEndPointer]['code'], [
						T_VARIABLE,
						// Variadic parameter
						T_ELLIPSIS,
					], true)
				) {
					if (
						!in_array($tokens[$previousTokenBeforeStartPointer]['code'], [
							T_EXTENDS,
							T_IMPLEMENTS,
							T_INSTANCEOF,
							// Trait
							T_USE,
							T_NEW,
							// Return type hint
							T_COLON,
							// Nullable type hint
							T_NULLABLE,
						], true)
						&& $tokens[$nextTokenAfterEndPointer]['code'] !== T_DOUBLE_COLON
					) {
						if ($tokens[$previousTokenBeforeStartPointer]['code'] === T_COMMA) {
							$precedingTokenPointer = TokenHelper::findPreviousExcluding(
								$phpcsFile,
								array_merge([T_COMMA], $nameTokenCodes, TokenHelper::$ineffectiveTokenCodes),
								$previousTokenBeforeStartPointer - 1
							);
							if (
								!in_array($tokens[$precedingTokenPointer]['code'], [
									T_IMPLEMENTS,
									T_EXTENDS,
									T_USE,
								], true)
							) {
								$type = ReferencedName::TYPE_CONSTANT;
							}
						} elseif ($tokens[$previousTokenBeforeStartPointer]['code'] === T_BITWISE_OR
							|| $tokens[$previousTokenBeforeStartPointer]['code'] === T_OPEN_PARENTHESIS
						) {
							$exclude = [T_BITWISE_OR, T_OPEN_PARENTHESIS];
							$catchPointer = TokenHelper::findPreviousExcluding(
								$phpcsFile,
								array_merge($exclude, $nameTokenCodes, TokenHelper::$ineffectiveTokenCodes),
								$previousTokenBeforeStartPointer - 1
							);
							$exclude = [T_BITWISE_OR];
							$openParenthesisPointer = TokenHelper::findPreviousExcluding(
								$phpcsFile,
								array_merge($exclude, $nameTokenCodes, TokenHelper::$ineffectiveTokenCodes),
								$previousTokenBeforeStartPointer
							);
							if (
								$tokens[$catchPointer]['code'] !== T_CATCH
								|| $tokens[$openParenthesisPointer]['code'] !== T_OPEN_PARENTHESIS
							) {
								$type = ReferencedName::TYPE_CONSTANT;
							}
						} else {
							$type = ReferencedName::TYPE_CONSTANT;
						}
					}
				}
			}
			$types[] = new ReferencedName(
				self::getReferenceName($phpcsFile, $nameStartPointer, $nameEndPointer),
				$nameStartPointer,
				$nameEndPointer,
				$type
			);
			$beginSearchAtPointer = $nameEndPointer + 1;
		}
		return $types;
	}

	private static function isReferencedName(File $phpcsFile, int $startPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$nextPointer = TokenHelper::findNextEffective($phpcsFile, $startPointer + 1);
		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $startPointer - 1);

		if ($tokens[$nextPointer]['code'] === T_DOUBLE_COLON) {
			return $tokens[$previousPointer]['code'] !== T_OBJECT_OPERATOR;
		}

		if (
			count($tokens[$startPointer]['conditions']) > 0
			&& array_values(array_reverse($tokens[$startPointer]['conditions']))[0] === T_USE
		) {
			// Method imported from trait
			return false;
		}

		$previousToken = $tokens[$previousPointer];

		$skipTokenCodes = [
			T_FUNCTION,
			T_AS,
			T_DOUBLE_COLON,
			T_OBJECT_OPERATOR,
			T_NAMESPACE,
			T_CONST,
		];

		if ($previousToken['code'] === T_USE) {
			$classPointer = TokenHelper::findPrevious($phpcsFile, [T_CLASS, T_TRAIT, T_ANON_CLASS], $startPointer - 1);
			if ($classPointer !== null) {
				$classToken = $tokens[$classPointer];
				return $startPointer > $classToken['scope_opener'] && $startPointer < $classToken['scope_closer'];
			}

			return false;
		}

		if (
			$previousToken['code'] === T_OPEN_PARENTHESIS
			&& isset($previousToken['parenthesis_owner'])
			&& $tokens[$previousToken['parenthesis_owner']]['code'] === T_DECLARE
		) {
			return false;
		}

		if (
			$previousToken['code'] === T_COMMA
			&& TokenHelper::findPreviousLocal($phpcsFile, T_DECLARE, $previousPointer - 1) !== null
		) {
			return false;
		}

		if ($previousToken['code'] === T_COMMA) {
			$constPointer = TokenHelper::findPreviousLocal($phpcsFile, T_CONST, $previousPointer - 1);
			if (
				$constPointer !== null
				&& TokenHelper::findNext($phpcsFile, [T_OPEN_SHORT_ARRAY, T_ARRAY], $constPointer + 1, $startPointer) === null
			) {
				return false;
			}
		} elseif ($previousToken['code'] === T_BITWISE_AND) {
			$pointerBefore = TokenHelper::findPreviousEffective($phpcsFile, $previousPointer - 1);
			$isFunctionPointerBefore = TokenHelper::findPreviousLocal($phpcsFile, T_FUNCTION, $previousPointer - 1) !== null;

			if ($tokens[$pointerBefore]['code'] !== T_VARIABLE && $isFunctionPointerBefore) {
				return false;
			}
		} elseif ($previousToken['code'] === T_GOTO) {
			return false;
		}

		$isProbablyReferencedName = !in_array(
			$previousToken['code'],
			array_merge($skipTokenCodes, TokenHelper::$typeKeywordTokenCodes),
			true
		);

		if (!$isProbablyReferencedName) {
			return false;
		}

		$endPointer = self::getReferencedNameEndPointer($phpcsFile, $startPointer);
		$referencedName = self::getReferenceName($phpcsFile, $startPointer, $endPointer);

		if (TypeHintHelper::isSimpleTypeHint($referencedName)) {
			return $tokens[$nextPointer]['code'] === T_OPEN_PARENTHESIS;
		}

		return $referencedName !== 'object';
	}

}
