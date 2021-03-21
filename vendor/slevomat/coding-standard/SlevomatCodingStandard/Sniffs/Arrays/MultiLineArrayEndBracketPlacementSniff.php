<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_OPEN_SHORT_ARRAY;

class MultiLineArrayEndBracketPlacementSniff implements Sniff
{

	public const CODE_ARRAY_END_WRONG_PLACEMENT = 'ArrayEndWrongPlacement';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_OPEN_SHORT_ARRAY];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $arrayStart
	 */
	public function process(File $phpcsFile, $arrayStart): void
	{
		$tokens = $phpcsFile->getTokens();

		$arrayEnd = $tokens[$arrayStart]['bracket_closer'];

		if ($tokens[$arrayStart]['line'] === $tokens[$arrayEnd]['line']) {
			return;
		}

		$nextArrayStart = TokenHelper::findNextEffective($phpcsFile, $arrayStart + 1, $arrayEnd);
		if ($nextArrayStart === null || $tokens[$nextArrayStart]['code'] !== T_OPEN_SHORT_ARRAY) {
			return;
		}

		$nextArrayEnd = $tokens[$nextArrayStart]['bracket_closer'];
		$arraysStartAtSameLine = $tokens[$arrayStart]['line'] === $tokens[$nextArrayStart]['line'];
		$arraysEndAtSameLine = $tokens[$arrayEnd]['line'] === $tokens[$nextArrayEnd]['line'];
		if (!$arraysStartAtSameLine || $arraysEndAtSameLine) {
			return;
		}

		$error = "Expected nested array to end at the same line as it's parent. Either put the nested array's end at the same line as the parent's end, or put the nested array start on it's own line.";
		$fix = $phpcsFile->addFixableError($error, $arrayStart, self::CODE_ARRAY_END_WRONG_PLACEMENT);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->addContent($arrayStart, $phpcsFile->eolChar);
	}

}
