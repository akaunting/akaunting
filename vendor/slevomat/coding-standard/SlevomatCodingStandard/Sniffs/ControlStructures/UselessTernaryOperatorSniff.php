<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ConditionHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use const T_CASE;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSE_SHORT_ARRAY;
use const T_CLOSE_SQUARE_BRACKET;
use const T_COMMA;
use const T_DOUBLE_ARROW;
use const T_EQUAL;
use const T_FALSE;
use const T_INLINE_ELSE;
use const T_INLINE_THEN;
use const T_OPEN_TAG;
use const T_RETURN;
use const T_TRUE;

class UselessTernaryOperatorSniff implements Sniff
{

	public const CODE_USELESS_TERNARY_OPERATOR = 'UselessTernaryOperator';

	/** @var bool */
	public $assumeAllConditionExpressionsAreAlreadyBoolean = false;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_INLINE_THEN,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $inlineThenPointer
	 */
	public function process(File $phpcsFile, $inlineThenPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$pointerAfterInlineThen = TokenHelper::findNextEffective($phpcsFile, $inlineThenPointer + 1);
		if ($tokens[$pointerAfterInlineThen]['code'] === T_INLINE_ELSE) {
			$inlineElsePointer = $pointerAfterInlineThen;
		} else {
			if (!in_array($tokens[$pointerAfterInlineThen]['code'], [T_TRUE, T_FALSE], true)) {
				return;
			}

			$inlineElsePointer = TokenHelper::findNextEffective($phpcsFile, $pointerAfterInlineThen + 1);
			if ($tokens[$inlineElsePointer]['code'] !== T_INLINE_ELSE) {
				return;
			}
		}

		$pointerAfterInlineElse = TokenHelper::findNextEffective($phpcsFile, $inlineElsePointer + 1);
		if (!in_array($tokens[$pointerAfterInlineElse]['code'], [T_TRUE, T_FALSE], true)) {
			return;
		}

		/** @var int $pointerAfterInlineElseBranch */
		$pointerAfterInlineElseBranch = TokenHelper::findNextEffective($phpcsFile, $pointerAfterInlineElse + 1);

		if (in_array($tokens[$pointerAfterInlineElseBranch]['code'], [T_CLOSE_SHORT_ARRAY, T_CLOSE_SQUARE_BRACKET], true)) {
			$pointerBeforeCondition = $tokens[$pointerAfterInlineElseBranch]['bracket_opener'];
		} elseif ($tokens[$pointerAfterInlineElseBranch]['code'] === T_CLOSE_PARENTHESIS) {
			$pointerBeforeCondition = $tokens[$pointerAfterInlineElseBranch]['parenthesis_opener'];
		} else {
			$pointerBeforeCondition = TokenHelper::findPrevious(
				$phpcsFile,
				[T_EQUAL, T_DOUBLE_ARROW, T_COMMA, T_RETURN, T_CASE, T_OPEN_TAG],
				$inlineThenPointer - 1
			);
		}

		/** @var int $conditionStartPointer */
		$conditionStartPointer = TokenHelper::findNextEffective($phpcsFile, $pointerBeforeCondition + 1);
		/** @var int $conditionEndPointer */
		$conditionEndPointer = TokenHelper::findPreviousEffective($phpcsFile, $inlineThenPointer - 1);

		$errorParameters = [
			'Useless ternary operator.',
			$inlineThenPointer,
			self::CODE_USELESS_TERNARY_OPERATOR,
		];

		if (
			!$this->assumeAllConditionExpressionsAreAlreadyBoolean
			&& !ConditionHelper::conditionReturnsBoolean($phpcsFile, $conditionStartPointer, $conditionEndPointer)
		) {
			if ($tokens[$pointerAfterInlineThen]['code'] !== T_INLINE_ELSE) {
				$phpcsFile->addError(...$errorParameters);
			}
			return;
		}

		$fix = $phpcsFile->addFixableError(...$errorParameters);

		if (!$fix) {
			return;
		}

		$negativeCondition = ConditionHelper::getNegativeCondition($phpcsFile, $conditionStartPointer, $conditionEndPointer);

		$phpcsFile->fixer->beginChangeset();

		if ($tokens[$pointerAfterInlineThen]['code'] === T_FALSE) {
			$phpcsFile->fixer->replaceToken($conditionStartPointer, $negativeCondition);
			for ($i = $conditionStartPointer + 1; $i <= $conditionEndPointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		for ($i = $conditionEndPointer + 1; $i < $pointerAfterInlineElseBranch; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

}
