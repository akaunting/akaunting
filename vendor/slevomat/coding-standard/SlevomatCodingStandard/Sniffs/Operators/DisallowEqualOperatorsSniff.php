<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Operators;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use function sprintf;
use const T_IS_EQUAL;
use const T_IS_NOT_EQUAL;

class DisallowEqualOperatorsSniff implements Sniff
{

	public const CODE_DISALLOWED_EQUAL_OPERATOR = 'DisallowedEqualOperator';
	public const CODE_DISALLOWED_NOT_EQUAL_OPERATOR = 'DisallowedNotEqualOperator';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_IS_EQUAL,
			T_IS_NOT_EQUAL,
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

		if ($tokens[$operatorPointer]['code'] === T_IS_EQUAL) {
			$fix = $phpcsFile->addFixableError(
				'Operator == is disallowed, use === instead.',
				$operatorPointer,
				self::CODE_DISALLOWED_EQUAL_OPERATOR
			);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($operatorPointer, '===');
				$phpcsFile->fixer->endChangeset();
			}
		} else {
			$fix = $phpcsFile->addFixableError(sprintf(
				'Operator %s is disallowed, use !== instead.',
				$tokens[$operatorPointer]['content']
			), $operatorPointer, self::CODE_DISALLOWED_NOT_EQUAL_OPERATOR);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($operatorPointer, '!==');
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
