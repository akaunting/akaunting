<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use const T_AS;
use const T_CONST;
use const T_FUNCTION;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_USE;
use const T_VAR;
use const T_VARIABLE;

class PropertySpacingSniff extends AbstractPropertyAndConstantSpacing
{

	public const CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_PROPERTY = 'IncorrectCountOfBlankLinesAfterProperty';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_VAR, T_PUBLIC, T_PROTECTED, T_PRIVATE];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$asPointer = TokenHelper::findPreviousEffective($phpcsFile, $pointer - 1);
		if ($tokens[$asPointer]['code'] === T_AS) {
			return $pointer;
		}

		$propertyPointer = TokenHelper::findNext($phpcsFile, [T_VARIABLE, T_FUNCTION, T_CONST, T_USE], $pointer + 1);
		if ($propertyPointer === null || $tokens[$propertyPointer]['code'] !== T_VARIABLE) {
			return $propertyPointer ?? $pointer;
		}

		return parent::process($phpcsFile, $propertyPointer);
	}

	protected function isNextMemberValid(File $phpcsFile, int $pointer): bool
	{
		$nextPointer = TokenHelper::findNext($phpcsFile, [T_FUNCTION, T_VARIABLE], $pointer + 1);

		if ($nextPointer === null) {
			return false;
		}

		return $phpcsFile->getTokens()[$nextPointer]['code'] === T_VARIABLE;
	}

	protected function addError(File $phpcsFile, int $pointer, int $minExpectedLines, int $maxExpectedLines, int $found): bool
	{
		if ($minExpectedLines === $maxExpectedLines) {
			$errorMessage = $minExpectedLines === 1
				? 'Expected 1 blank line after property, found %3$d.'
				: 'Expected %2$d blank lines after property, found %3$d.';
		} else {
			$errorMessage = 'Expected %1$d to %2$d blank lines after property, found %3$d.';
		}
		$error = sprintf($errorMessage, $minExpectedLines, $maxExpectedLines, $found);

		return $phpcsFile->addFixableError($error, $pointer, self::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_PROPERTY);
	}

}
