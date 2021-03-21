<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_AS;
use const T_CONST;
use const T_FUNCTION;
use const T_NULLABLE;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_VAR;
use const T_VARIABLE;
use const T_WHITESPACE;

class PropertyTypeHintSpacingSniff implements Sniff
{

	public const CODE_NO_SPACE_BEFORE_NULLABILITY_SYMBOL = 'NoSpaceBeforeNullabilitySymbol';

	public const CODE_MULTIPLE_SPACES_BEFORE_NULLABILITY_SYMBOL = 'MultipleSpacesBeforeNullabilitySymbol';

	public const CODE_NO_SPACE_BEFORE_TYPE_HINT = 'NoSpaceBeforeTypeHint';

	public const CODE_MULTIPLE_SPACES_BEFORE_TYPE_HINT = 'MultipleSpacesBeforeTypeHint';

	public const CODE_NO_SPACE_BETWEEN_TYPE_HINT_AND_PROPERTY = 'NoSpaceBetweenTypeHintAndProperty';

	public const CODE_MULTIPLE_SPACES_BETWEEN_TYPE_HINT_AND_PROPERTY = 'MultipleSpacesBetweenTypeHintAndProperty';

	public const CODE_WHITESPACE_AFTER_NULLABILITY_SYMBOL = 'WhitespaceAfterNullabilitySymbol';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_VAR,
			T_PUBLIC,
			T_PROTECTED,
			T_PRIVATE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $visibilityPointer
	 */
	public function process(File $phpcsFile, $visibilityPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$asPointer = TokenHelper::findPreviousEffective($phpcsFile, $visibilityPointer - 1);
		if ($tokens[$asPointer]['code'] === T_AS) {
			return;
		}

		$propertyPointer = TokenHelper::findNext($phpcsFile, [T_FUNCTION, T_CONST, T_VARIABLE], $visibilityPointer + 1);

		if ($tokens[$propertyPointer]['code'] !== T_VARIABLE) {
			return;
		}

		$typeHintTokenCodes = TokenHelper::getTypeHintTokenCodes();

		$propertyStartPointer = $visibilityPointer;

		$typeHintEndPointer = TokenHelper::findPrevious($phpcsFile, $typeHintTokenCodes, $propertyPointer - 1, $propertyStartPointer);
		if ($typeHintEndPointer === null) {
			return;
		}

		$typeHintStartPointer = TokenHelper::findPreviousExcluding(
			$phpcsFile,
			$typeHintTokenCodes,
			$typeHintEndPointer,
			$propertyStartPointer
		) + 1;

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $typeHintStartPointer - 1, $propertyStartPointer);
		$nullabilitySymbolPointer = $previousPointer !== null && $tokens[$previousPointer]['code'] === T_NULLABLE ? $previousPointer : null;

		if ($tokens[$propertyStartPointer + 1]['code'] !== T_WHITESPACE) {
			if ($nullabilitySymbolPointer !== null) {
				$errorMessage = 'There must be exactly one space before type hint nullability symbol.';
				$errorCode = self::CODE_NO_SPACE_BEFORE_NULLABILITY_SYMBOL;
			} else {
				$errorMessage = 'There must be exactly one space before type hint.';
				$errorCode = self::CODE_NO_SPACE_BEFORE_TYPE_HINT;
			}

			$fix = $phpcsFile->addFixableError($errorMessage, $typeHintEndPointer, $errorCode);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContent($propertyStartPointer, ' ');
				$phpcsFile->fixer->endChangeset();
			}
		} elseif ($tokens[$propertyStartPointer + 1]['content'] !== ' ') {
			if ($nullabilitySymbolPointer !== null) {
				$errorMessage = 'There must be exactly one space before type hint nullability symbol.';
				$errorCode = self::CODE_MULTIPLE_SPACES_BEFORE_NULLABILITY_SYMBOL;
			} else {
				$errorMessage = 'There must be exactly one space before type hint.';
				$errorCode = self::CODE_MULTIPLE_SPACES_BEFORE_TYPE_HINT;
			}

			$fix = $phpcsFile->addFixableError($errorMessage, $propertyStartPointer, $errorCode);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($propertyStartPointer + 1, ' ');
				$phpcsFile->fixer->endChangeset();
			}
		}

		if ($tokens[$typeHintEndPointer + 1]['code'] !== T_WHITESPACE) {
			$fix = $phpcsFile->addFixableError(
				'There must be exactly one space between type hint and property.',
				$typeHintEndPointer,
				self::CODE_NO_SPACE_BETWEEN_TYPE_HINT_AND_PROPERTY
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContent($typeHintEndPointer, ' ');
				$phpcsFile->fixer->endChangeset();
			}
		} elseif ($tokens[$typeHintEndPointer + 1]['content'] !== ' ') {
			$fix = $phpcsFile->addFixableError(
				'There must be exactly one space between type hint and property.',
				$typeHintEndPointer,
				self::CODE_MULTIPLE_SPACES_BETWEEN_TYPE_HINT_AND_PROPERTY
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($typeHintEndPointer + 1, ' ');
				$phpcsFile->fixer->endChangeset();
			}
		}

		if ($nullabilitySymbolPointer === null) {
			return;
		}

		if ($nullabilitySymbolPointer + 1 === $typeHintStartPointer) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'There must be no whitespace between type hint nullability symbol and type hint.',
			$typeHintStartPointer,
			self::CODE_WHITESPACE_AFTER_NULLABILITY_SYMBOL
		);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($nullabilitySymbolPointer + 1, '');
		$phpcsFile->fixer->endChangeset();
	}

}
