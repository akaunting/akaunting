<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use function array_fill_keys;
use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_reverse;
use function array_values;
use function count;
use function end;
use function implode;
use function in_array;
use function key;
use function reset;
use const T_ARRAY;
use const T_ARRAY_CAST;
use const T_BOOL_CAST;
use const T_BOOLEAN_AND;
use const T_BOOLEAN_OR;
use const T_CASE;
use const T_CLOSE_CURLY_BRACKET;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSE_SHORT_ARRAY;
use const T_COALESCE;
use const T_COLON;
use const T_COMMA;
use const T_COMMENT;
use const T_CONSTANT_ENCAPSED_STRING;
use const T_DNUMBER;
use const T_DOC_COMMENT;
use const T_DOUBLE_CAST;
use const T_DOUBLE_COLON;
use const T_FALSE;
use const T_INLINE_ELSE;
use const T_INLINE_THEN;
use const T_INT_CAST;
use const T_LNUMBER;
use const T_LOGICAL_AND;
use const T_LOGICAL_OR;
use const T_LOGICAL_XOR;
use const T_MINUS;
use const T_NS_SEPARATOR;
use const T_NULL;
use const T_OBJECT_CAST;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;
use const T_OPEN_TAG;
use const T_PLUS;
use const T_RETURN;
use const T_SEMICOLON;
use const T_STRING;
use const T_STRING_CAST;
use const T_TRUE;
use const T_UNSET_CAST;
use const T_VARIABLE;
use const T_WHITESPACE;

class YodaHelper
{

	private const DYNAMISM_VARIABLE = 999;

	private const DYNAMISM_CONSTANT = 1;

	private const DYNAMISM_FUNCTION_CALL = 998;

	/**
	 * @param File $phpcsFile
	 * @param array<int, array<string, array<int, int|string>|int|string>> $leftSideTokens
	 * @param array<int, array<string, array<int, int|string>|int|string>> $rightSideTokens
	 */
	public static function fix(File $phpcsFile, array $leftSideTokens, array $rightSideTokens): void
	{
		$phpcsFile->fixer->beginChangeset();
		self::replace($phpcsFile, $leftSideTokens, $rightSideTokens);
		self::replace($phpcsFile, $rightSideTokens, $leftSideTokens);
		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @param array<int, array<string, array<int, int|string>|int|string>> $tokens
	 * @param int $comparisonTokenPointer
	 * @return array<int, array<string, array<int, int|string>|int|string>>
	 */
	public static function getLeftSideTokens(array $tokens, int $comparisonTokenPointer): array
	{
		$parenthesisDepth = 0;
		$shortArrayDepth = 0;
		$examinedTokenPointer = $comparisonTokenPointer;
		$sideTokens = [];
		$stopTokenCodes = self::getStopTokenCodes();
		while (true) {
			$examinedTokenPointer--;
			$examinedToken = $tokens[$examinedTokenPointer];
			/** @var string|int $examinedTokenCode */
			$examinedTokenCode = $examinedToken['code'];
			if ($parenthesisDepth === 0 && $shortArrayDepth === 0 && isset($stopTokenCodes[$examinedTokenCode])) {
				break;
			}

			if ($examinedTokenCode === T_CLOSE_SHORT_ARRAY) {
				$shortArrayDepth++;
			} elseif ($examinedTokenCode === T_OPEN_SHORT_ARRAY) {
				if ($shortArrayDepth === 0) {
					break;
				}

				$shortArrayDepth--;
			}

			if ($examinedTokenCode === T_CLOSE_PARENTHESIS) {
				$parenthesisDepth++;
			} elseif ($examinedTokenCode === T_OPEN_PARENTHESIS) {
				if ($parenthesisDepth === 0) {
					break;
				}

				$parenthesisDepth--;
			}

			$sideTokens[$examinedTokenPointer] = $examinedToken;
		}

		return self::trimWhitespaceTokens(array_reverse($sideTokens, true));
	}

	/**
	 * @param array<int, array<string, array<int, int|string>|int|string>> $tokens
	 * @param int $comparisonTokenPointer
	 * @return array<int, array<string, array<int, int|string>|int|string>>
	 */
	public static function getRightSideTokens(array $tokens, int $comparisonTokenPointer): array
	{
		$parenthesisDepth = 0;
		$shortArrayDepth = 0;
		$examinedTokenPointer = $comparisonTokenPointer;
		$sideTokens = [];
		$stopTokenCodes = self::getStopTokenCodes();
		while (true) {
			$examinedTokenPointer++;
			$examinedToken = $tokens[$examinedTokenPointer];
			/** @var string|int $examinedTokenCode */
			$examinedTokenCode = $examinedToken['code'];
			if ($parenthesisDepth === 0 && $shortArrayDepth === 0 && isset($stopTokenCodes[$examinedTokenCode])) {
				break;
			}

			if ($examinedTokenCode === T_OPEN_SHORT_ARRAY) {
				$shortArrayDepth++;
			} elseif ($examinedTokenCode === T_CLOSE_SHORT_ARRAY) {
				if ($shortArrayDepth === 0) {
					break;
				}

				$shortArrayDepth--;
			}

			if ($examinedTokenCode === T_OPEN_PARENTHESIS) {
				$parenthesisDepth++;
			} elseif ($examinedTokenCode === T_CLOSE_PARENTHESIS) {
				if ($parenthesisDepth === 0) {
					break;
				}

				$parenthesisDepth--;
			}

			$sideTokens[$examinedTokenPointer] = $examinedToken;
		}

		return self::trimWhitespaceTokens($sideTokens);
	}

	/**
	 * @param array<int, array<string, array<int, int|string>|int|string>> $tokens
	 * @param array<int, array<string, array<int, int|string>|int|string>> $sideTokens
	 * @return int|null
	 */
	public static function getDynamismForTokens(array $tokens, array $sideTokens): ?int
	{
		$sideTokens = array_values(array_filter($sideTokens, static function (array $token): bool {
			return !in_array(
				$token['code'],
				[T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, T_NS_SEPARATOR, T_PLUS, T_MINUS, T_INT_CAST, T_DOUBLE_CAST, T_STRING_CAST, T_ARRAY_CAST, T_OBJECT_CAST, T_BOOL_CAST, T_UNSET_CAST],
				true
			);
		}));

		$sideTokensCount = count($sideTokens);

		$dynamism = self::getTokenDynamism();

		if ($sideTokensCount > 0) {
			if ($sideTokens[0]['code'] === T_VARIABLE) {
				// Expression starts with a variable - wins over everything else
				return self::DYNAMISM_VARIABLE;
			}

			if ($sideTokens[$sideTokensCount - 1]['code'] === T_CLOSE_PARENTHESIS) {
				if (array_key_exists('parenthesis_owner', $sideTokens[$sideTokensCount - 1])) {
					/** @var int $parenthesisOwner */
					$parenthesisOwner = $sideTokens[$sideTokensCount - 1]['parenthesis_owner'];
					if ($tokens[$parenthesisOwner]['code'] === T_ARRAY) {
						// Array
						return $dynamism[T_ARRAY];
					}
				}

				// Function or method call
				return self::DYNAMISM_FUNCTION_CALL;
			}

			if ($sideTokensCount === 1 && $sideTokens[0]['code'] === T_STRING) {
				// Constant
				return self::DYNAMISM_CONSTANT;
			}
		}

		if ($sideTokensCount > 2 && $sideTokens[$sideTokensCount - 2]['code'] === T_DOUBLE_COLON) {
			if ($sideTokens[$sideTokensCount - 1]['code'] === T_VARIABLE) {
				// Static property access
				return self::DYNAMISM_VARIABLE;
			}

			if ($sideTokens[$sideTokensCount - 1]['code'] === T_STRING) {
				// Class constant
				return self::DYNAMISM_CONSTANT;
			}
		}

		if (array_key_exists(0, $sideTokens)) {
			/** @var int $sideTokenCode */
			$sideTokenCode = $sideTokens[0]['code'];
			if (array_key_exists($sideTokenCode, $dynamism)) {
				return $dynamism[$sideTokenCode];
			}
		}

		return null;
	}

	/**
	 * @param array<int, array<string, array<int, int|string>|int|string>> $tokens
	 * @return array<int, array<string, array<int, int|string>|int|string>>
	 */
	public static function trimWhitespaceTokens(array $tokens): array
	{
		foreach ($tokens as $pointer => $token) {
			if ($token['code'] !== T_WHITESPACE) {
				break;
			}

			unset($tokens[$pointer]);
		}

		foreach (array_reverse($tokens, true) as $pointer => $token) {
			if ($token['code'] !== T_WHITESPACE) {
				break;
			}

			unset($tokens[$pointer]);
		}

		return $tokens;
	}

	/**
	 * @param File $phpcsFile
	 * @param array<int, array<string, array<int, int|string>|int|string>> $oldTokens
	 * @param array<int, array<string, array<int, int|string>|int|string>> $newTokens
	 */
	private static function replace(File $phpcsFile, array $oldTokens, array $newTokens): void
	{
		reset($oldTokens);
		/** @var int $firstOldPointer */
		$firstOldPointer = key($oldTokens);
		end($oldTokens);
		/** @var int $lastOldPointer */
		$lastOldPointer = key($oldTokens);

		for ($i = $firstOldPointer; $i <= $lastOldPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->addContent($firstOldPointer, implode('', array_map(static function (array $token): string {
			return $token['content'];
		}, $newTokens)));
	}

	/**
	 * @return array<int|string, int>
	 */
	private static function getTokenDynamism(): array
	{
		static $tokenDynamism;

		if ($tokenDynamism === null) {
			$tokenDynamism = [
				T_TRUE => 0,
				T_FALSE => 0,
				T_NULL => 0,
				T_DNUMBER => 0,
				T_LNUMBER => 0,
				T_OPEN_SHORT_ARRAY => 0,
				// Do not stack error messages when the old-style array syntax is used
				T_ARRAY => 0,
				T_CONSTANT_ENCAPSED_STRING => 0,
				T_VARIABLE => self::DYNAMISM_VARIABLE,
				T_STRING => self::DYNAMISM_FUNCTION_CALL,
			];

			$tokenDynamism += array_fill_keys(array_keys(Tokens::$castTokens), 3);
		}

		return $tokenDynamism;
	}

	/**
	 * @return array<int|string, bool>
	 */
	private static function getStopTokenCodes(): array
	{
		static $stopTokenCodes;

		if ($stopTokenCodes === null) {
			$stopTokenCodes = [
				T_BOOLEAN_AND => true,
				T_BOOLEAN_OR => true,
				T_SEMICOLON => true,
				T_OPEN_TAG => true,
				T_INLINE_THEN => true,
				T_INLINE_ELSE => true,
				T_LOGICAL_AND => true,
				T_LOGICAL_OR => true,
				T_LOGICAL_XOR => true,
				T_COALESCE => true,
				T_CASE => true,
				T_COLON => true,
				T_RETURN => true,
				T_COMMA => true,
				T_CLOSE_CURLY_BRACKET => true,
			];

			$stopTokenCodes += array_fill_keys(array_keys(Tokens::$assignmentTokens), true);
		}

		return $stopTokenCodes;
	}

}
