<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function sprintf;
use const T_BREAK;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSE_SHORT_ARRAY;
use const T_CONTINUE;
use const T_ECHO;
use const T_EXIT;
use const T_INCLUDE;
use const T_INCLUDE_ONCE;
use const T_OPEN_PARENTHESIS;
use const T_PRINT;
use const T_REQUIRE;
use const T_REQUIRE_ONCE;
use const T_RETURN;
use const T_SEMICOLON;
use const T_THROW;
use const T_WHITESPACE;
use const T_YIELD;
use const T_YIELD_FROM;

class LanguageConstructWithParenthesesSniff implements Sniff
{

	public const CODE_USED_WITH_PARENTHESES = 'UsedWithParentheses';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_BREAK,
			T_CONTINUE,
			T_ECHO,
			T_EXIT,
			T_INCLUDE,
			T_INCLUDE_ONCE,
			T_PRINT,
			T_REQUIRE,
			T_REQUIRE_ONCE,
			T_RETURN,
			T_THROW,
			T_YIELD,
			T_YIELD_FROM,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $languageConstructPointer
	 */
	public function process(File $phpcsFile, $languageConstructPointer): void
	{
		$tokens = $phpcsFile->getTokens();
		/** @var int $openParenthesisPointer */
		$openParenthesisPointer = TokenHelper::findNextEffective($phpcsFile, $languageConstructPointer + 1);
		if ($tokens[$openParenthesisPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		$closeParenthesisPointer = $tokens[$openParenthesisPointer]['parenthesis_closer'];
		$afterCloseParenthesisPointer = TokenHelper::findNextEffective($phpcsFile, $closeParenthesisPointer + 1);
		if (!in_array($tokens[$afterCloseParenthesisPointer]['code'], [T_SEMICOLON, T_CLOSE_PARENTHESIS, T_CLOSE_SHORT_ARRAY], true)) {
			return;
		}

		$containsContentBetweenParentheses = TokenHelper::findNextEffective(
			$phpcsFile,
			$openParenthesisPointer + 1,
			$closeParenthesisPointer
		) !== null;
		if ($tokens[$languageConstructPointer]['code'] === T_EXIT && $containsContentBetweenParentheses) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf('Usage of language construct "%s" with parentheses is disallowed.', $tokens[$languageConstructPointer]['content']),
			$languageConstructPointer,
			self::CODE_USED_WITH_PARENTHESES
		);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($openParenthesisPointer, '');
		if ($tokens[$openParenthesisPointer - 1]['code'] !== T_WHITESPACE && $containsContentBetweenParentheses) {
			$phpcsFile->fixer->addContent($openParenthesisPointer, ' ');
		}
		$phpcsFile->fixer->replaceToken($closeParenthesisPointer, '');
		$phpcsFile->fixer->endChangeset();
	}

}
