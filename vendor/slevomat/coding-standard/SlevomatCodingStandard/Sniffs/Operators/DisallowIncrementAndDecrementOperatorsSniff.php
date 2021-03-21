<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Operators;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_DEC;
use const T_INC;

class DisallowIncrementAndDecrementOperatorsSniff implements Sniff
{

	public const CODE_DISALLOWED_PRE_INCREMENT_OPERATOR = 'DisallowedPreIncrementOperator';
	public const CODE_DISALLOWED_POST_INCREMENT_OPERATOR = 'DisallowedPostIncrementOperator';
	public const CODE_DISALLOWED_PRE_DECREMENT_OPERATOR = 'DisallowedPreDecrementOperator';
	public const CODE_DISALLOWED_POST_DECREMENT_OPERATOR = 'DisallowedPostDecrementOperator';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_DEC,
			T_INC,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $operatorPointer
	 */
	public function process(File $phpcsFile, $operatorPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		/** @var int $nextPointer */
		$nextPointer = TokenHelper::findNextEffective($phpcsFile, $operatorPointer + 1);
		$afterVariableEndPointer = IdentificatorHelper::findEndPointer($phpcsFile, $nextPointer);
		$isPostOperator = $afterVariableEndPointer === null;

		if ($tokens[$operatorPointer]['code'] === T_INC) {
			if ($isPostOperator) {
				$code = self::CODE_DISALLOWED_POST_INCREMENT_OPERATOR;
				$message = 'Use of post-increment operator is disallowed.';
			} else {
				$code = self::CODE_DISALLOWED_PRE_INCREMENT_OPERATOR;
				$message = 'Use of pre-increment operator is disallowed.';
			}
		} else {
			if ($isPostOperator) {
				$code = self::CODE_DISALLOWED_POST_DECREMENT_OPERATOR;
				$message = 'Use of post-decrement operator is disallowed.';
			} else {
				$code = self::CODE_DISALLOWED_PRE_DECREMENT_OPERATOR;
				$message = 'Use of pre-decrement operator is disallowed.';
			}
		}

		$phpcsFile->addError($message, $operatorPointer, $code);
	}

}
