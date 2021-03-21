<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Operators;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function in_array;
use const T_CLOSE_CURLY_BRACKET;
use const T_CLOSE_PARENTHESIS;
use const T_COLON;
use const T_DEC;
use const T_FOR;
use const T_INC;
use const T_OPEN_CURLY_BRACKET;
use const T_OPEN_TAG;
use const T_SEMICOLON;
use const T_WHILE;

class RequireOnlyStandaloneIncrementAndDecrementOperatorsSniff implements Sniff
{

	public const CODE_PRE_INCREMENT_OPERATOR_NOT_USED_STANDALONE = 'PreIncrementOperatorNotUsedStandalone';
	public const CODE_POST_INCREMENT_OPERATOR_NOT_USED_STANDALONE = 'PostIncrementOperatorNotUsedStandalone';
	public const CODE_PRE_DECREMENT_OPERATOR_NOT_USED_STANDALONE = 'PreDecrementOperatorNotUsedAsStandalone';
	public const CODE_POST_DECREMENT_OPERATOR_NOT_USED_STANDALONE = 'PostDecrementOperatorNotUsedStandalone';

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

		if ($isPostOperator) {
			/** @var int $beforeVariableEndPointer */
			$beforeVariableEndPointer = TokenHelper::findPreviousEffective($phpcsFile, $operatorPointer - 1);
			/** @var int $instructionStartPointer */
			$instructionStartPointer = IdentificatorHelper::findStartPointer($phpcsFile, $beforeVariableEndPointer);
			$instructionEndPointer = $operatorPointer;
		} else {
			$instructionStartPointer = $operatorPointer;
			/** @var int $instructionEndPointer */
			$instructionEndPointer = $afterVariableEndPointer;
		}

		if ($this->isStandalone($phpcsFile, $instructionStartPointer, $instructionEndPointer)) {
			return;
		}

		if ($tokens[$operatorPointer]['code'] === T_INC) {
			if ($isPostOperator) {
				$code = self::CODE_POST_INCREMENT_OPERATOR_NOT_USED_STANDALONE;
				$message = 'Post-increment operator should be used only as single instruction.';
			} else {
				$code = self::CODE_PRE_INCREMENT_OPERATOR_NOT_USED_STANDALONE;
				$message = 'Pre-increment operator should be used only as single instruction.';
			}
		} else {
			if ($isPostOperator) {
				$code = self::CODE_POST_DECREMENT_OPERATOR_NOT_USED_STANDALONE;
				$message = 'Post-decrement operator should be used only as single instruction.';
			} else {
				$code = self::CODE_PRE_DECREMENT_OPERATOR_NOT_USED_STANDALONE;
				$message = 'Pre-decrement operator should be used only as single instruction.';
			}
		}

		$phpcsFile->addError($message, $operatorPointer, $code);
	}

	private function isStandalone(File $phpcsFile, int $instructionStartPointer, int $instructionEndPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$pointerBeforeInstructionStart = TokenHelper::findPreviousEffective($phpcsFile, $instructionStartPointer - 1);
		if (!in_array(
			$tokens[$pointerBeforeInstructionStart]['code'],
			[T_SEMICOLON, T_COLON, T_OPEN_CURLY_BRACKET, T_CLOSE_CURLY_BRACKET, T_OPEN_TAG],
			true
		)) {
			return false;
		}

		$pointerAfterInstructionEnd = TokenHelper::findNextEffective($phpcsFile, $instructionEndPointer + 1);
		if ($tokens[$pointerAfterInstructionEnd]['code'] === T_SEMICOLON) {
			return true;
		}

		if ($tokens[$pointerAfterInstructionEnd]['code'] === T_CLOSE_PARENTHESIS) {
			return array_key_exists('parenthesis_owner', $tokens[$pointerAfterInstructionEnd])
				&& in_array($tokens[$tokens[$pointerAfterInstructionEnd]['parenthesis_owner']]['code'], [T_FOR, T_WHILE], true);
		}

		return false;
	}

}
