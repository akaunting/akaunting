<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function count;
use function in_array;
use const T_ANON_CLASS;
use const T_CLOSE_CURLY_BRACKET;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSURE;
use const T_FN;
use const T_FOR;
use const T_OPEN_CURLY_BRACKET;
use const T_OPEN_TAG;
use const T_SEMICOLON;
use const T_WHITESPACE;

class UselessSemicolonSniff implements Sniff
{

	public const CODE_USELESS_SEMICOLON = 'UselessSemicolon';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_SEMICOLON,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $semicolonPointer
	 */
	public function process(File $phpcsFile, $semicolonPointer): void
	{
		$this->checkMultipleSemicolons($phpcsFile, $semicolonPointer);
		$this->checkSemicolonAtTheBeginningOfScope($phpcsFile, $semicolonPointer);
		$this->checkSemicolonAfterScope($phpcsFile, $semicolonPointer);
	}

	private function checkMultipleSemicolons(File $phpcsFile, int $semicolonPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $semicolonPointer - 1);
		if ($tokens[$previousPointer]['code'] !== T_SEMICOLON) {
			return;
		}

		$possibleEndScopePointer = TokenHelper::findNextLocal($phpcsFile, T_CLOSE_PARENTHESIS, $semicolonPointer + 1);
		if (
			$possibleEndScopePointer !== null
			&& $tokens[$possibleEndScopePointer]['parenthesis_opener'] < $semicolonPointer
			&& array_key_exists('parenthesis_owner', $tokens[$possibleEndScopePointer])
			&& $tokens[$tokens[$possibleEndScopePointer]['parenthesis_owner']]['code'] === T_FOR
		) {
			return;
		}

		$fix = $phpcsFile->addFixableError('Useless semicolon.', $semicolonPointer, self::CODE_USELESS_SEMICOLON);

		if (!$fix) {
			return;
		}

		$this->removeUselessSemicolon($phpcsFile, $semicolonPointer);
	}

	private function checkSemicolonAtTheBeginningOfScope(File $phpcsFile, int $semicolonPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $semicolonPointer - 1);
		if (!in_array($tokens[$previousPointer]['code'], [T_OPEN_TAG, T_OPEN_CURLY_BRACKET], true)) {
			return;
		}

		$fix = $phpcsFile->addFixableError('Useless semicolon.', $semicolonPointer, self::CODE_USELESS_SEMICOLON);

		if (!$fix) {
			return;
		}

		$this->removeUselessSemicolon($phpcsFile, $semicolonPointer);
	}

	private function checkSemicolonAfterScope(File $phpcsFile, int $semicolonPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $semicolonPointer - 1);
		if ($tokens[$previousPointer]['code'] !== T_CLOSE_CURLY_BRACKET) {
			return;
		}

		if (!array_key_exists('scope_condition', $tokens[$previousPointer])) {
			return;
		}

		$scopeOpenerPointer = $tokens[$previousPointer]['scope_condition'];
		if (in_array($tokens[$scopeOpenerPointer]['code'], [T_CLOSURE, T_FN, T_ANON_CLASS], true)) {
			return;
		}

		$fix = $phpcsFile->addFixableError('Useless semicolon.', $semicolonPointer, self::CODE_USELESS_SEMICOLON);

		if (!$fix) {
			return;
		}

		$this->removeUselessSemicolon($phpcsFile, $semicolonPointer);
	}

	private function removeUselessSemicolon(File $phpcsFile, int $semicolonPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$fixStartPointer = $semicolonPointer;
		do {
			if ($tokens[$fixStartPointer - 1]['code'] !== T_WHITESPACE) {
				break;
			}

			$fixStartPointer--;

			if ($tokens[$fixStartPointer]['content'] === $phpcsFile->eolChar) {
				break;
			}
		} while (true);

		$fixEndPointer = $semicolonPointer;
		while ($fixEndPointer < count($tokens) - 1) {
			if ($tokens[$fixEndPointer + 1]['code'] !== T_WHITESPACE) {
				break;
			}

			if ($tokens[$fixEndPointer + 1]['content'] === $phpcsFile->eolChar) {
				break;
			}

			$fixEndPointer++;
		}

		$phpcsFile->fixer->beginChangeset();
		for ($i = $fixStartPointer; $i <= $fixEndPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
