<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Numbers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use function str_replace;
use function strpos;
use const T_DNUMBER;
use const T_LNUMBER;

class DisallowNumericLiteralSeparatorSniff implements Sniff
{

	public const CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR = 'DisallowedNumericLiteralSeparator';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_LNUMBER,
			T_DNUMBER,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $numberPointer
	 */
	public function process(File $phpcsFile, $numberPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if (strpos($tokens[$numberPointer]['content'], '_') === false) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Use of numeric literal separator is disallowed.',
			$numberPointer,
			self::CODE_DISALLOWED_NUMERIC_LITERAL_SEPARATOR
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($numberPointer, str_replace('_', '', $tokens[$numberPointer]['content']));
		$phpcsFile->fixer->endChangeset();
	}

}
