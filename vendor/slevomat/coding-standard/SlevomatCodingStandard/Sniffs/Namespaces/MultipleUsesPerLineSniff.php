<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use const T_COMMA;
use const T_SEMICOLON;
use const T_USE;

class MultipleUsesPerLineSniff implements Sniff
{

	public const CODE_MULTIPLE_USES_PER_LINE = 'MultipleUsesPerLine';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_USE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $usePointer
	 */
	public function process(File $phpcsFile, $usePointer): void
	{
		if (
			UseStatementHelper::isAnonymousFunctionUse($phpcsFile, $usePointer)
			|| UseStatementHelper::isTraitUse($phpcsFile, $usePointer)
		) {
			return;
		}

		$endPointer = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $usePointer + 1);
		$commaPointer = TokenHelper::findNext($phpcsFile, T_COMMA, $usePointer + 1, $endPointer);

		if ($commaPointer === null) {
			return;
		}

		$phpcsFile->addError('Multiple used types per use statement are forbidden.', $commaPointer, self::CODE_MULTIPLE_USES_PER_LINE);
	}

}
