<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\VariableHelper;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSURE;
use const T_DOUBLE_QUOTED_STRING;
use const T_FN;
use const T_OPEN_PARENTHESIS;
use const T_PARENT;
use const T_STATIC;
use const T_STRING;
use const T_VARIABLE;

class StaticClosureSniff implements Sniff
{

	public const CODE_CLOSURE_NOT_STATIC = 'ClosureNotStatic';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLOSURE,
			T_FN,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $closurePointer
	 */
	public function process(File $phpcsFile, $closurePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $closurePointer - 1);
		if ($tokens[$previousPointer]['code'] === T_STATIC) {
			return;
		}

		if ($tokens[$previousPointer]['code'] === T_OPEN_PARENTHESIS) {
			$pointerBeforeParenthesis = TokenHelper::findPreviousEffective($phpcsFile, $previousPointer - 1);
			if (
				$tokens[$pointerBeforeParenthesis]['code'] === T_STRING
				&& $tokens[$pointerBeforeParenthesis]['content'] === 'bind'
			) {
				return;
			}
		}

		$closureScopeOpenerPointer = $tokens[$closurePointer]['scope_opener'];
		$closureScopeCloserPointer = $tokens[$closurePointer]['scope_closer'];
		if ($tokens[$closureScopeCloserPointer]['code'] === T_CLOSE_PARENTHESIS) {
			while ($tokens[$closureScopeCloserPointer]['parenthesis_opener'] > $closurePointer) {
				$closureScopeCloserPointer = TokenHelper::findNext($phpcsFile, T_CLOSE_PARENTHESIS, $closureScopeCloserPointer + 1);
			}
		}

		$thisPointer = TokenHelper::findNextContent(
			$phpcsFile,
			T_VARIABLE,
			'$this',
			$closureScopeOpenerPointer + 1,
			$closureScopeCloserPointer
		);
		if ($thisPointer !== null) {
			return;
		}

		$stringPointers = TokenHelper::findNextAll(
			$phpcsFile,
			T_DOUBLE_QUOTED_STRING,
			$closureScopeOpenerPointer + 1,
			$closureScopeCloserPointer
		);
		foreach ($stringPointers as $stringPointer) {
			if (VariableHelper::isUsedInScopeInString($phpcsFile, '$this', $stringPointer)) {
				return;
			}
		}

		$parentPointer = TokenHelper::findNext($phpcsFile, T_PARENT, $closureScopeOpenerPointer + 1, $closureScopeCloserPointer);
		if ($parentPointer !== null) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Closure not using "$this" should be declared static.',
			$closurePointer,
			self::CODE_CLOSURE_NOT_STATIC
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->addContentBefore($closurePointer, 'static ');
		$phpcsFile->fixer->endChangeset();
	}

}
