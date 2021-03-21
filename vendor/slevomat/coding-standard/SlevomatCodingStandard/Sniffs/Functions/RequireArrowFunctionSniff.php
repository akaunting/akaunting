<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ScopeHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function count;
use const T_BITWISE_AND;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSURE;
use const T_FN;
use const T_RETURN;
use const T_SEMICOLON;
use const T_USE;
use const T_WHITESPACE;

class RequireArrowFunctionSniff implements Sniff
{

	public const CODE_REQUIRED_ARROW_FUNCTION = 'RequiredArrowFunction';

	/** @var bool */
	public $allowNested = true;

	/** @var bool|null */
	public $enable = null;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLOSURE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $closurePointer
	 */
	public function process(File $phpcsFile, $closurePointer): void
	{
		$this->enable = SniffSettingsHelper::isEnabledByPhpVersion($this->enable, 70400);

		if (!$this->enable) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$returnPointer = TokenHelper::findNextEffective($phpcsFile, $tokens[$closurePointer]['scope_opener'] + 1);
		if ($tokens[$returnPointer]['code'] !== T_RETURN) {
			return;
		}

		$usePointer = TokenHelper::findNextEffective($phpcsFile, $tokens[$closurePointer]['parenthesis_closer'] + 1);
		if ($tokens[$usePointer]['code'] === T_USE) {
			$useOpenParenthesisPointer = TokenHelper::findNextEffective($phpcsFile, $usePointer + 1);
			if (TokenHelper::findNext(
				$phpcsFile,
				T_BITWISE_AND,
				$useOpenParenthesisPointer + 1,
				$tokens[$useOpenParenthesisPointer]['parenthesis_closer']
			) !== null) {
				return;
			}
		}

		if (!$this->allowNested) {
			$closureOrArrowFunctionPointer = TokenHelper::findNext(
				$phpcsFile,
				[T_CLOSURE, T_FN],
				$tokens[$closurePointer]['scope_opener'] + 1,
				$tokens[$closurePointer]['scope_closer']
			);
			if ($closureOrArrowFunctionPointer !== null) {
				return;
			}
		}

		$fix = $phpcsFile->addFixableError('Use arrow function.', $closurePointer, self::CODE_REQUIRED_ARROW_FUNCTION);
		if (!$fix) {
			return;
		}

		$pointerAfterReturn = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $returnPointer + 1);
		$semicolonAfterReturn = $this->findSemicolon($phpcsFile, $returnPointer);
		$usePointer = TokenHelper::findNext(
			$phpcsFile,
			T_USE,
			$tokens[$closurePointer]['parenthesis_closer'] + 1,
			$tokens[$closurePointer]['scope_opener']
		);
		$nonWhitespacePointerBeforeScopeOpener = TokenHelper::findPreviousExcluding(
			$phpcsFile,
			T_WHITESPACE,
			$tokens[$closurePointer]['scope_opener'] - 1
		);

		$nonWhitespacePointerAfterUseParanthesisCloser = null;
		if ($usePointer !== null) {
			$useParenthesiCloserPointer = TokenHelper::findNext($phpcsFile, T_CLOSE_PARENTHESIS, $usePointer + 1);
			$nonWhitespacePointerAfterUseParanthesisCloser = TokenHelper::findNextExcluding(
				$phpcsFile,
				T_WHITESPACE,
				$useParenthesiCloserPointer + 1
			);
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($closurePointer, 'fn');

		if ($nonWhitespacePointerAfterUseParanthesisCloser !== null) {
			for ($i = $tokens[$closurePointer]['parenthesis_closer'] + 1; $i < $nonWhitespacePointerAfterUseParanthesisCloser; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		for ($i = $nonWhitespacePointerBeforeScopeOpener + 1; $i < $pointerAfterReturn; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->addContent($nonWhitespacePointerBeforeScopeOpener, ' => ');

		for ($i = $semicolonAfterReturn; $i <= $tokens[$closurePointer]['scope_closer']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	private function findSemicolon(File $phpcsFile, int $pointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$semicolonPointer = null;
		for ($i = $pointer + 1; $i < count($tokens) - 1; $i++) {
			if ($tokens[$i]['code'] !== T_SEMICOLON) {
				continue;
			}

			if (!ScopeHelper::isInSameScope($phpcsFile, $pointer, $i)) {
				continue;
			}

			$semicolonPointer = $i;
			break;
		}

		/** @var int $semicolonPointer */
		$semicolonPointer = $semicolonPointer;
		return $semicolonPointer;
	}

}
