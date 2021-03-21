<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\VariableHelper;
use function in_array;
use function sprintf;
use const T_BITWISE_AND;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSURE;
use const T_COMMA;
use const T_OPEN_PARENTHESIS;
use const T_USE;
use const T_VARIABLE;

class UnusedInheritedVariablePassedToClosureSniff implements Sniff
{

	public const CODE_UNUSED_INHERITED_VARIABLE = 'UnusedInheritedVariable';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_USE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $usePointer
	 */
	public function process(File $phpcsFile, $usePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		/** @var int $parenthesisOpenerPointer */
		$parenthesisOpenerPointer = TokenHelper::findNextEffective($phpcsFile, $usePointer + 1);
		if ($tokens[$parenthesisOpenerPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		/** @var int $closurePointer */
		$closurePointer = TokenHelper::findPrevious($phpcsFile, T_CLOSURE, $usePointer - 1);

		$currentPointer = $parenthesisOpenerPointer + 1;
		do {
			$variablePointer = TokenHelper::findNext(
				$phpcsFile,
				T_VARIABLE,
				$currentPointer,
				$tokens[$parenthesisOpenerPointer]['parenthesis_closer']
			);
			if ($variablePointer === null) {
				break;
			}

			$this->checkVariableUsage(
				$phpcsFile,
				$usePointer,
				$parenthesisOpenerPointer,
				$tokens[$parenthesisOpenerPointer]['parenthesis_closer'],
				$variablePointer,
				$closurePointer
			);

			$currentPointer = $variablePointer + 1;
		} while (true);
	}

	private function checkVariableUsage(
		File $phpcsFile,
		int $usePointer,
		int $useParenthesisOpenerPointer,
		int $useParenthesisCloserPointer,
		int $variablePointer,
		int $scopeOwnerPointer
	): void
	{
		$tokens = $phpcsFile->getTokens();

		if (VariableHelper::isUsedInScope($phpcsFile, $scopeOwnerPointer, $variablePointer)) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf('Unused inherited variable %s passed to closure.', $tokens[$variablePointer]['content']),
			$variablePointer,
			self::CODE_UNUSED_INHERITED_VARIABLE
		);

		if (!$fix) {
			return;
		}

		$fixStartPointer = $variablePointer;
		do {
			if ($tokens[$fixStartPointer - 1]['code'] === T_OPEN_PARENTHESIS) {
				break;
			}

			$fixStartPointer--;

			if ($tokens[$fixStartPointer]['code'] === T_COMMA) {
				break;
			}
		} while (true);

		$fixEndPointer = $variablePointer;
		do {
			if ($tokens[$fixEndPointer + 1]['code'] === T_CLOSE_PARENTHESIS) {
				break;
			}

			if ($tokens[$fixEndPointer + 1]['code'] === T_COMMA && $tokens[$fixStartPointer]['code'] === T_COMMA) {
				break;
			}

			if (in_array($tokens[$fixEndPointer + 1]['code'], [T_VARIABLE, T_BITWISE_AND], true)) {
				break;
			}

			$fixEndPointer++;
		} while (true);

		$phpcsFile->fixer->beginChangeset();
		for ($i = $fixStartPointer; $i <= $fixEndPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$emptyUse = true;
		for ($i = $useParenthesisOpenerPointer + 1; $i < $useParenthesisCloserPointer; $i++) {
			if ($phpcsFile->fixer->getTokenContent($i) !== '') {
				$emptyUse = false;
				break;
			}
		}
		if ($emptyUse) {
			for ($i = $usePointer; $i <= $useParenthesisCloserPointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		$phpcsFile->fixer->endChangeset();
	}

}
