<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_reverse;
use function in_array;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_DOUBLE_COLON;
use const T_INTERFACE;
use const T_STATIC;
use const T_TRAIT;

class UselessLateStaticBindingSniff implements Sniff
{

	public const CODE_USELESS_LATE_STATIC_BINDING = 'UselessLateStaticBinding';

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

		$classPointer = null;
		foreach (array_reverse($tokens[$staticPointer]['conditions'], true) as $conditionPointer => $conditionTokenCode) {
			if (!in_array($conditionTokenCode, [T_CLASS, T_INTERFACE, T_TRAIT, T_ANON_CLASS], true)) {
				continue;
			}

			$classPointer = $conditionPointer;
			break;
		}

		if (!ClassHelper::isFinal($phpcsFile, $classPointer)) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Useless late static binding because class is final.',
			$staticPointer,
			self::CODE_USELESS_LATE_STATIC_BINDING
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($staticPointer, 'self');
		$phpcsFile->fixer->endChangeset();
	}

}
