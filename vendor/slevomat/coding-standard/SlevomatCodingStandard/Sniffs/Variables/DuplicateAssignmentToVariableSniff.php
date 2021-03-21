<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Variables;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function sprintf;
use const T_DOUBLE_COLON;
use const T_EQUAL;
use const T_OBJECT_OPERATOR;
use const T_VARIABLE;

class DuplicateAssignmentToVariableSniff implements Sniff
{

	public const CODE_DUPLICATE_ASSIGNMENT = 'DuplicateAssignment';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_EQUAL,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $assignmentPointer
	 */
	public function process(File $phpcsFile, $assignmentPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$variablePointer = TokenHelper::findPreviousEffective($phpcsFile, $assignmentPointer - 1);
		if ($tokens[$variablePointer]['code'] !== T_VARIABLE) {
			return;
		}

		$pointerBeforeVariable = TokenHelper::findPreviousEffective($phpcsFile, $variablePointer - 1);
		if (in_array($tokens[$pointerBeforeVariable]['code'], [T_OBJECT_OPERATOR, T_DOUBLE_COLON], true)) {
			return;
		}

		/** @var int $secondVariablePointer */
		$secondVariablePointer = TokenHelper::findNextEffective($phpcsFile, $assignmentPointer + 1);
		if ($tokens[$secondVariablePointer]['code'] !== T_VARIABLE) {
			return;
		}

		if ($tokens[$variablePointer]['content'] !== $tokens[$secondVariablePointer]['content']) {
			return;
		}

		$pointerAfterSecondVariable = TokenHelper::findNextEffective($phpcsFile, $secondVariablePointer + 1);
		if ($tokens[$pointerAfterSecondVariable]['code'] !== T_EQUAL) {
			return;
		}

		$phpcsFile->addError(
			sprintf('Duplicate assignment to variable %s.', $tokens[$secondVariablePointer]['content']),
			$secondVariablePointer,
			self::CODE_DUPLICATE_ASSIGNMENT
		);
	}

}
