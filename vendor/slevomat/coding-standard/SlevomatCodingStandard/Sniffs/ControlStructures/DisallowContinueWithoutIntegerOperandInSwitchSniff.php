<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_reverse;
use function current;
use const T_CONTINUE;
use const T_LNUMBER;
use const T_SWITCH;

class DisallowContinueWithoutIntegerOperandInSwitchSniff implements Sniff
{

	public const CODE_DISALLOWED_CONTINUE_WITHOUT_INTEGER_OPERAND_IN_SWITCH = 'DisallowedContinueWithoutIntegerOperandInSwitch';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CONTINUE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $continuePointer
	 */
	public function process(File $phpcsFile, $continuePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$operandPointer = TokenHelper::findNextEffective($phpcsFile, $continuePointer + 1);

		if ($tokens[$operandPointer]['code'] === T_LNUMBER) {
			return;
		}

		$conditionTokenCode = current(array_reverse($tokens[$continuePointer]['conditions']));
		if ($conditionTokenCode !== T_SWITCH) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Usage of "continue" without integer operand in "switch" is disallowed, use "break" instead.',
			$continuePointer,
			self::CODE_DISALLOWED_CONTINUE_WITHOUT_INTEGER_OPERAND_IN_SWITCH
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($continuePointer, 'break');
		$phpcsFile->fixer->endChangeset();
	}

}
