<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function strtolower;
use const T_DOUBLE_COLON;
use const T_OPEN_PARENTHESIS;
use const T_STATIC;
use const T_STRING;

class DisallowLateStaticBindingForConstantsSniff implements Sniff
{

	public const CODE_DISALLOWED_LATE_STATIC_BINDING_FOR_CONSTANT = 'DisallowedLateStaticBindingForConstant';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_STATIC,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $staticPointer
	 */
	public function process(File $phpcsFile, $staticPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$doubleColonPointer = TokenHelper::findNextEffective($phpcsFile, $staticPointer + 1);
		if ($tokens[$doubleColonPointer]['code'] !== T_DOUBLE_COLON) {
			return;
		}

		$stringPointer = TokenHelper::findNextEffective($phpcsFile, $doubleColonPointer + 1);
		if ($tokens[$stringPointer]['code'] !== T_STRING) {
			return;
		}

		if (strtolower($tokens[$stringPointer]['content']) === 'class') {
			return;
		}

		$pointerAfterString = TokenHelper::findNextEffective($phpcsFile, $stringPointer + 1);
		if ($tokens[$pointerAfterString]['code'] === T_OPEN_PARENTHESIS) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Late static binding for constants is disallowed.',
			$staticPointer,
			self::CODE_DISALLOWED_LATE_STATIC_BINDING_FOR_CONSTANT
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($staticPointer, 'self');
		$phpcsFile->fixer->endChangeset();
	}

}
