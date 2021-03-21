<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function array_key_exists;
use function array_keys;
use function array_reverse;
use function in_array;

/**
 * @internal
 */
class ParameterHelper
{

	public static function isParameter(File $phpcsFile, int $variablePointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		if (!array_key_exists('nested_parenthesis', $tokens[$variablePointer])) {
			return false;
		}

		$parenthesisOpenerPointer = array_reverse(array_keys($tokens[$variablePointer]['nested_parenthesis']))[0];
		if (!array_key_exists('parenthesis_owner', $tokens[$parenthesisOpenerPointer])) {
			return false;
		}

		$parenthesisOwnerPointer = $tokens[$parenthesisOpenerPointer]['parenthesis_owner'];
		return in_array($tokens[$parenthesisOwnerPointer]['code'], TokenHelper::$functionTokenCodes, true);
	}

}
