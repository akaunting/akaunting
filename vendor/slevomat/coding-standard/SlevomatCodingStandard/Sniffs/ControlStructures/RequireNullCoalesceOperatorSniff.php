<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function sprintf;
use function trim;
use const T_BOOLEAN_NOT;
use const T_CLOSE_PARENTHESIS;
use const T_CLOSE_SHORT_ARRAY;
use const T_CLOSE_SQUARE_BRACKET;
use const T_COALESCE;
use const T_COMMA;
use const T_DOUBLE_ARROW;
use const T_INLINE_ELSE;
use const T_INLINE_THEN;
use const T_IS_IDENTICAL;
use const T_IS_NOT_IDENTICAL;
use const T_ISSET;
use const T_NULL;
use const T_SEMICOLON;

class RequireNullCoalesceOperatorSniff implements Sniff
{

	public const CODE_NULL_COALESCE_OPERATOR_NOT_USED = 'NullCoalesceOperatorNotUsed';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_ISSET,
			T_IS_IDENTICAL,
			T_IS_NOT_IDENTICAL,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$pointer]['code'] === T_ISSET) {
			$this->checkIsset($phpcsFile, $pointer);
		} else {
			$this->checkIdenticalOperator($phpcsFile, $pointer);
		}
	}

	public function checkIsset(File $phpcsFile, int $issetPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $issetPointer - 1);
		if ($tokens[$previousPointer]['code'] === T_BOOLEAN_NOT) {
			return;
		}

		if (in_array($tokens[$previousPointer]['code'], Tokens::$booleanOperators, true)) {
			return;
		}

		$openParenthesisPointer = TokenHelper::findNextEffective($phpcsFile, $issetPointer + 1);
		$closeParenthesisPointer = $tokens[$openParenthesisPointer]['parenthesis_closer'];

		/** @var int $inlineThenPointer */
		$inlineThenPointer = TokenHelper::findNextEffective($phpcsFile, $closeParenthesisPointer + 1);
		if ($tokens[$inlineThenPointer]['code'] !== T_INLINE_THEN) {
			return;
		}

		$commaPointer = TokenHelper::findNext($phpcsFile, T_COMMA, $openParenthesisPointer + 1, $closeParenthesisPointer);
		if ($commaPointer !== null) {
			return;
		}

		$inlineElsePointer = TokenHelper::findNext($phpcsFile, T_INLINE_ELSE, $inlineThenPointer + 1);

		$variableContent = IdentificatorHelper::getContent($phpcsFile, $openParenthesisPointer + 1, $closeParenthesisPointer - 1);
		$thenContent = IdentificatorHelper::getContent($phpcsFile, $inlineThenPointer + 1, $inlineElsePointer - 1);

		if ($variableContent !== $thenContent) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Use null coalesce operator instead of ternary operator.',
			$inlineThenPointer,
			self::CODE_NULL_COALESCE_OPERATOR_NOT_USED
		);
		if (!$fix) {
			return;
		}

		$startPointer = $issetPointer;
		if (in_array($tokens[$previousPointer]['code'], Tokens::$castTokens, true)) {
			$startPointer = $previousPointer;
		}

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->replaceToken($startPointer, sprintf('%s ??', $variableContent));

		for ($i = $startPointer + 1; $i <= $inlineElsePointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	public function checkIdenticalOperator(File $phpcsFile, int $identicalOperator): void
	{
		$tokens = $phpcsFile->getTokens();

		/** @var int $pointerBeforeIdenticalOperator */
		$pointerBeforeIdenticalOperator = TokenHelper::findPreviousEffective($phpcsFile, $identicalOperator - 1);
		/** @var int $pointerAfterIdenticalOperator */
		$pointerAfterIdenticalOperator = TokenHelper::findNextEffective($phpcsFile, $identicalOperator + 1);

		if (
			$tokens[$pointerBeforeIdenticalOperator]['code'] !== T_NULL
			&& $tokens[$pointerAfterIdenticalOperator]['code'] !== T_NULL
		) {
			return;
		}

		$isYodaCondition = $tokens[$pointerBeforeIdenticalOperator]['code'] === T_NULL;

		$variableEndPointer = $isYodaCondition ? $pointerAfterIdenticalOperator : $pointerBeforeIdenticalOperator;
		$tmpPointer = $variableEndPointer;
		while ($tokens[$tmpPointer]['code'] === T_CLOSE_PARENTHESIS) {
			/** @var int $tmpPointer */
			$tmpPointer = TokenHelper::findPreviousEffective($phpcsFile, $tokens[$tmpPointer]['parenthesis_opener'] - 1);
		}
		$variableStartPointer = IdentificatorHelper::findStartPointer($phpcsFile, $tmpPointer);

		if ($variableStartPointer === null) {
			return;
		}

		$pointerBeforeCondition = TokenHelper::findPreviousEffective(
			$phpcsFile,
			($isYodaCondition ? $pointerBeforeIdenticalOperator : $variableStartPointer) - 1
		);

		if (in_array($tokens[$pointerBeforeCondition]['code'], Tokens::$booleanOperators, true)) {
			return;
		}

		/** @var int $inlineThenPointer */
		$inlineThenPointer = TokenHelper::findNextEffective(
			$phpcsFile,
			($isYodaCondition ? $variableEndPointer : $pointerAfterIdenticalOperator) + 1
		);
		if ($tokens[$inlineThenPointer]['code'] !== T_INLINE_THEN) {
			return;
		}

		$inlineElsePointer = TokenHelper::findNext($phpcsFile, T_INLINE_ELSE, $inlineThenPointer + 1);
		$inlineElseEndPointer = $inlineElsePointer + 1;
		while (true) {
			if (in_array(
				$tokens[$inlineElseEndPointer]['code'],
				[T_SEMICOLON, T_COMMA, T_DOUBLE_ARROW, T_CLOSE_SHORT_ARRAY, T_COALESCE],
				true
			)) {
				break;
			}

			if (
				$tokens[$inlineElseEndPointer]['code'] === T_CLOSE_PARENTHESIS
				&& $tokens[$inlineElseEndPointer]['parenthesis_opener'] < $inlineElsePointer
			) {
				break;
			}

			if (
				$tokens[$inlineElseEndPointer]['code'] === T_CLOSE_SQUARE_BRACKET
				&& $tokens[$inlineElseEndPointer]['bracket_opener'] < $inlineElsePointer
			) {
				break;
			}

			$inlineElseEndPointer++;
		}

		$variableContent = IdentificatorHelper::getContent($phpcsFile, $variableStartPointer, $variableEndPointer);

		/** @var int $compareToStartPointer */
		$compareToStartPointer = TokenHelper::findNextEffective(
			$phpcsFile,
			($tokens[$identicalOperator]['code'] === T_IS_IDENTICAL ? $inlineElsePointer : $inlineThenPointer) + 1
		);
		/** @var int $compareToEndPointer */
		$compareToEndPointer = TokenHelper::findPreviousEffective(
			$phpcsFile,
			($tokens[$identicalOperator]['code'] === T_IS_IDENTICAL ? $inlineElseEndPointer : $inlineElsePointer) - 1
		);

		$compareToContent = IdentificatorHelper::getContent($phpcsFile, $compareToStartPointer, $compareToEndPointer);

		if ($compareToContent !== $variableContent) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Use null coalesce operator instead of ternary operator.',
			$inlineThenPointer,
			self::CODE_NULL_COALESCE_OPERATOR_NOT_USED
		);

		if (!$fix) {
			return;
		}

		/** @var int $conditionStart */
		$conditionStart = $isYodaCondition ? $pointerBeforeIdenticalOperator : $variableStartPointer;
		$variableContent = trim(TokenHelper::getContent($phpcsFile, $variableStartPointer, $variableEndPointer));

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->replaceToken($conditionStart, sprintf('%s ??', $variableContent));

		if ($tokens[$identicalOperator]['code'] === T_IS_IDENTICAL) {
			for ($i = $conditionStart + 1; $i <= $inlineThenPointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}

			$pointerBeforeInlineElse = TokenHelper::findPreviousEffective($phpcsFile, $inlineElsePointer - 1);
			$pointerBeforeInlineElseEnd = TokenHelper::findPreviousEffective($phpcsFile, $inlineElseEndPointer - 1);

			for ($i = $pointerBeforeInlineElse + 1; $i <= $pointerBeforeInlineElseEnd; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}

		} else {
			for ($i = $conditionStart + 1; $i <= $inlineElsePointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		$phpcsFile->fixer->endChangeset();
	}

}
