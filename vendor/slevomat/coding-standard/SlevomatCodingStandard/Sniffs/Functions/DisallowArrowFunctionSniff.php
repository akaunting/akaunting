<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use const T_FN;

class DisallowArrowFunctionSniff implements Sniff
{

	public const CODE_DISALLOWED_ARROW_FUNCTION = 'DisallowedArrowFunction';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_FN,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $arrowFunctionPointer
	 */
	public function process(File $phpcsFile, $arrowFunctionPointer): void
	{
		$phpcsFile->addError('Use of arrow function is disallowed.', $arrowFunctionPointer, self::CODE_DISALLOWED_ARROW_FUNCTION);
	}

}
