<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function count;
use function in_array;
use function preg_match;
use function preg_quote;
use function strlen;
use function strtolower;
use function substr;
use const T_DOUBLE_COLON;
use const T_DOUBLE_QUOTED_STRING;
use const T_HEREDOC;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_TAG;
use const T_STRING;
use const T_VARIABLE;

/**
 * @internal
 */
class VariableHelper
{

	public static function isUsedInScope(File $phpcsFile, int $scopeOwnerPointer, int $variablePointer): bool
	{
		return self::isUsedInScopeInternal($phpcsFile, $scopeOwnerPointer, $variablePointer, null);
	}

	public static function isUsedInScopeAfterPointer(
		File $phpcsFile,
		int $scopeOwnerPointer,
		int $variablePointer,
		int $startCheckPointer
	): bool
	{
		return self::isUsedInScopeInternal($phpcsFile, $scopeOwnerPointer, $variablePointer, $startCheckPointer);
	}

	public static function isUsedAsVariable(File $phpcsFile, int $variablePointer, int $variableToCheckPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$variablePointer]['content'] !== $tokens[$variableToCheckPointer]['content']) {
			return false;
		}

		if ($tokens[$variableToCheckPointer - 1]['code'] === T_DOUBLE_COLON) {
			$pointerAfterVariable = TokenHelper::findNextEffective($phpcsFile, $variableToCheckPointer + 1);
			return $tokens[$pointerAfterVariable]['code'] === T_OPEN_PARENTHESIS;
		}

		return !ParameterHelper::isParameter($phpcsFile, $variableToCheckPointer);
	}

	public static function isUsedInCompactFunction(File $phpcsFile, int $variablePointer, int $stringPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$stringContent = $tokens[$stringPointer]['content'];
		if (strtolower($stringContent) !== 'compact') {
			return false;
		}

		$parenthesisOpenerPointer = TokenHelper::findNextEffective($phpcsFile, $stringPointer + 1);
		if ($tokens[$parenthesisOpenerPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return false;
		}

		$variableNameWithoutDollar = substr($tokens[$variablePointer]['content'], 1);
		for ($i = $parenthesisOpenerPointer + 1; $i < $tokens[$parenthesisOpenerPointer]['parenthesis_closer']; $i++) {
			if (preg_match('~^([\'"])' . $variableNameWithoutDollar . '\\1$~', $tokens[$i]['content']) !== 0) {
				return true;
			}
		}

		return false;
	}

	public static function isUsedInScopeInString(File $phpcsFile, string $variableName, int $stringPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$stringContent = $tokens[$stringPointer]['content'];

		if (preg_match('~(\\\\)?(' . preg_quote($variableName, '~') . ')\b~', $stringContent, $matches) !== 0) {
			if ($matches[1] === '') {
				return true;
			}

			if (strlen($matches[1]) % 2 === 1) {
				return true;
			}
		}

		$variableNameWithoutDollar = substr($variableName, 1);
		return preg_match('~\$\{' . preg_quote($variableNameWithoutDollar, '~') . '(<=\}|\b)~', $stringContent) !== 0;
	}

	private static function isUsedInScopeInternal(
		File $phpcsFile,
		int $scopeOwnerPointer,
		int $variablePointer,
		?int $startCheckPointer
	): bool
	{
		$tokens = $phpcsFile->getTokens();

		$scopeCloserPointer = $tokens[$scopeOwnerPointer]['code'] === T_OPEN_TAG
			? count($tokens) - 1
			: $tokens[$scopeOwnerPointer]['scope_closer'] - 1;
		$firstPointerInScope = $tokens[$scopeOwnerPointer]['code'] === T_OPEN_TAG
			? $scopeOwnerPointer + 1
			: $tokens[$scopeOwnerPointer]['scope_opener'] + 1;

		if ($startCheckPointer === null) {
			$startCheckPointer = $firstPointerInScope;
		}

		for ($i = $startCheckPointer; $i <= $scopeCloserPointer; $i++) {
			if (!ScopeHelper::isInSameScope($phpcsFile, $i, $firstPointerInScope)) {
				continue;
			}

			if (
				$tokens[$i]['code'] === T_VARIABLE
				&& self::isUsedAsVariable($phpcsFile, $variablePointer, $i)
			) {
				return true;
			}

			if ($tokens[$i]['code'] === T_STRING) {
				if (self::isGetDefinedVarsCall($phpcsFile, $i)) {
					return true;
				}

				if (self::isUsedInCompactFunction($phpcsFile, $variablePointer, $i)) {
					return true;
				}
			}

			if (
				in_array($tokens[$i]['code'], [T_DOUBLE_QUOTED_STRING, T_HEREDOC], true)
				&& self::isUsedInScopeInString($phpcsFile, $tokens[$variablePointer]['content'], $i)
			) {
				return true;
			}
		}

		return false;
	}

	private static function isGetDefinedVarsCall(File $phpcsFile, int $stringPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$stringContent = $tokens[$stringPointer]['content'];
		if (strtolower($stringContent) !== 'get_defined_vars') {
			return false;
		}

		$parenthesisOpenerPointer = TokenHelper::findNextEffective($phpcsFile, $stringPointer + 1);
		return $tokens[$parenthesisOpenerPointer]['code'] === T_OPEN_PARENTHESIS;
	}

}
