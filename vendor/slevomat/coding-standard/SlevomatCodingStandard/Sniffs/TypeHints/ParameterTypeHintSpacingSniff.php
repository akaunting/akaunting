<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_keys;
use function sprintf;
use const T_BITWISE_AND;
use const T_COMMA;
use const T_ELLIPSIS;
use const T_NULLABLE;
use const T_VARIABLE;
use const T_WHITESPACE;

class ParameterTypeHintSpacingSniff implements Sniff
{

	public const CODE_NO_SPACE_BETWEEN_TYPE_HINT_AND_PARAMETER = 'NoSpaceBetweenTypeHintAndParameter';

	public const CODE_MULTIPLE_SPACES_BETWEEN_TYPE_HINT_AND_PARAMETER = 'MultipleSpacesBetweenTypeHintAndParameter';

	public const CODE_WHITESPACE_AFTER_NULLABILITY_SYMBOL = 'WhitespaceAfterNullabilitySymbol';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return TokenHelper::$functionTokenCodes;
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 */
	public function process(File $phpcsFile, $functionPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$parametersStartPointer = $tokens[$functionPointer]['parenthesis_opener'] + 1;
		$parametersEndPointer = $tokens[$functionPointer]['parenthesis_closer'] - 1;

		$typeHintTokenCodes = TokenHelper::getNameTokenCodes();

		for ($i = $parametersStartPointer; $i <= $parametersEndPointer; $i++) {
			if ($tokens[$i]['code'] !== T_VARIABLE) {
				continue;
			}

			$parameterPointer = $i;
			$parameterName = $tokens[$parameterPointer]['content'];

			$parameterStartPointer = TokenHelper::findPrevious($phpcsFile, T_COMMA, $parameterPointer - 1, $parametersStartPointer);
			if ($parameterStartPointer === null) {
				$parameterStartPointer = $parametersStartPointer;
			}

			$parameterEndPointer = TokenHelper::findNext($phpcsFile, T_COMMA, $parameterPointer + 1, $parametersEndPointer + 1);
			if ($parameterEndPointer === null) {
				$parameterEndPointer = $parametersEndPointer;
			}

			$typeHintEndPointer = TokenHelper::findPrevious($phpcsFile, $typeHintTokenCodes, $parameterPointer - 1, $parameterStartPointer);
			if ($typeHintEndPointer === null) {
				continue;
			}

			$nextTokenNames = [
				T_VARIABLE => sprintf('parameter %s', $parameterName),
				T_BITWISE_AND => sprintf('reference sign of parameter %s', $parameterName),
				T_ELLIPSIS => sprintf('varadic parameter %s', $parameterName),
			];
			$nextTokenPointer = TokenHelper::findNext(
				$phpcsFile,
				array_keys($nextTokenNames),
				$typeHintEndPointer + 1,
				$parameterEndPointer + 1
			);

			if ($tokens[$typeHintEndPointer + 1]['code'] !== T_WHITESPACE) {
				$fix = $phpcsFile->addFixableError(
					sprintf(
						'There must be exactly one space between parameter type hint and %s.',
						$nextTokenNames[$tokens[$nextTokenPointer]['code']]
					),
					$typeHintEndPointer,
					self::CODE_NO_SPACE_BETWEEN_TYPE_HINT_AND_PARAMETER
				);
				if ($fix) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->addContent($typeHintEndPointer, ' ');
					$phpcsFile->fixer->endChangeset();
				}
			} elseif ($tokens[$typeHintEndPointer + 1]['content'] !== ' ') {
				$fix = $phpcsFile->addFixableError(
					sprintf(
						'There must be exactly one space between parameter type hint and %s.',
						$nextTokenNames[$tokens[$nextTokenPointer]['code']]
					),
					$typeHintEndPointer,
					self::CODE_MULTIPLE_SPACES_BETWEEN_TYPE_HINT_AND_PARAMETER
				);
				if ($fix) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->replaceToken($typeHintEndPointer + 1, ' ');
					$phpcsFile->fixer->endChangeset();
				}
			}

			$typeHintStartPointer = TokenHelper::findPreviousExcluding(
				$phpcsFile,
				$typeHintTokenCodes,
				$typeHintEndPointer,
				$parameterStartPointer
			) + 1;

			$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $typeHintStartPointer - 1, $parameterStartPointer);
			$nullabilitySymbolPointer = $previousPointer !== null && $tokens[$previousPointer]['code'] === T_NULLABLE
				? $previousPointer
				: null;

			if ($nullabilitySymbolPointer === null) {
				continue;
			}

			if ($nullabilitySymbolPointer + 1 === $typeHintStartPointer) {
				continue;
			}

			$fix = $phpcsFile->addFixableError(
				sprintf(
					'There must be no whitespace between parameter type hint nullability symbol and parameter type hint of parameter %s.',
					$parameterName
				),
				$typeHintStartPointer,
				self::CODE_WHITESPACE_AFTER_NULLABILITY_SYMBOL
			);
			if (!$fix) {
				continue;
			}

			$phpcsFile->fixer->beginChangeset();
			$phpcsFile->fixer->replaceToken($nullabilitySymbolPointer + 1, '');
			$phpcsFile->fixer->endChangeset();
		}
	}

}
