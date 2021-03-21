<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_ANON_CLASS;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSE_SHORT_ARRAY;
use const T_CLOSE_SQUARE_BRACKET;
use const T_COALESCE;
use const T_COMMA;
use const T_DOUBLE_ARROW;
use const T_INLINE_ELSE;
use const T_INLINE_THEN;
use const T_NEW;
use const T_OPEN_PARENTHESIS;
use const T_SEMICOLON;
use const T_WHITESPACE;

class NewWithoutParenthesesSniff implements Sniff
{

	public const CODE_USELESS_PARENTHESES = 'UselessParentheses';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_NEW,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $newPointer
	 */
	public function process(File $phpcsFile, $newPointer): void
	{
		$tokens = $phpcsFile->getTokens();
		/** @var int $nextPointer */
		$nextPointer = TokenHelper::findNextEffective($phpcsFile, $newPointer + 1);

		if ($tokens[$nextPointer]['code'] === T_ANON_CLASS) {
			return;
		}

		$parenthesisOpenerPointer = $nextPointer + 1;
		do {
			/** @var int $parenthesisOpenerPointer */
			$parenthesisOpenerPointer = TokenHelper::findNext(
				$phpcsFile,
				[
					T_OPEN_PARENTHESIS,
					T_SEMICOLON,
					T_COMMA,
					T_INLINE_THEN,
					T_INLINE_ELSE,
					T_COALESCE,
					T_CLOSE_SHORT_ARRAY,
					T_CLOSE_SQUARE_BRACKET,
					T_CLOSE_PARENTHESIS,
					T_DOUBLE_ARROW,
				],
				$parenthesisOpenerPointer
			);

			if (
				$tokens[$parenthesisOpenerPointer]['code'] !== T_CLOSE_SQUARE_BRACKET
				|| $tokens[$parenthesisOpenerPointer]['bracket_opener'] <= $newPointer
			) {
				break;
			}

			$parenthesisOpenerPointer++;
		} while (true);

		if ($tokens[$parenthesisOpenerPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		$nextPointer = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $parenthesisOpenerPointer + 1);
		if ($nextPointer !== $tokens[$parenthesisOpenerPointer]['parenthesis_closer']) {
			return;
		}

		$fix = $phpcsFile->addFixableError('Useless parentheses in "new".', $newPointer, self::CODE_USELESS_PARENTHESES);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		for ($i = $parenthesisOpenerPointer; $i <= $tokens[$parenthesisOpenerPointer]['parenthesis_closer']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
