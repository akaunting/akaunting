<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_reverse;
use function in_array;
use function ltrim;
use function sprintf;
use function strlen;
use const T_CLOSURE;
use const T_DOUBLE_COLON;
use const T_FN;
use const T_FUNCTION;
use const T_NEW;
use const T_OBJECT_OPERATOR;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;
use const T_STRING;

class RequireSingleLineCallSniff extends AbstractLineCall
{

	public const CODE_REQUIRED_SINGLE_LINE_CALL = 'RequiredSingleLineCall';

	/** @var int */
	public $maxLineLength = 120;

	/** @var bool */
	public $ignoreWithComplexParameter = true;

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $stringPointer
	 */
	public function process(File $phpcsFile, $stringPointer): void
	{
		if (!$this->isCall($phpcsFile, $stringPointer)) {
			return;
		}

		if ($this->shouldBeSkipped($phpcsFile, $stringPointer)) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$parenthesisOpenerPointer = TokenHelper::findNextEffective($phpcsFile, $stringPointer + 1);
		$parenthesisCloserPointer = $tokens[$parenthesisOpenerPointer]['parenthesis_closer'];

		if ($tokens[$parenthesisOpenerPointer]['line'] === $tokens[$parenthesisCloserPointer]['line']) {
			return;
		}

		if (TokenHelper::findNext(
			$phpcsFile,
			TokenHelper::$inlineCommentTokenCodes,
			$parenthesisOpenerPointer + 1,
			$parenthesisCloserPointer
		) !== null) {
			return;
		}

		if ($this->ignoreWithComplexParameter) {
			if (
				TokenHelper::findNext(
					$phpcsFile,
					[T_CLOSURE, T_FN, T_OPEN_SHORT_ARRAY],
					$parenthesisOpenerPointer + 1,
					$parenthesisCloserPointer
				) !== null
			) {
				return;
			}

			// Contains inner call
			foreach (TokenHelper::findNextAll(
				$phpcsFile,
				TokenHelper::getOnlyNameTokenCodes(),
				$parenthesisOpenerPointer + 1,
				$parenthesisCloserPointer
			) as $innerStringPointer) {
				$pointerAfterInnerString = TokenHelper::findNextEffective($phpcsFile, $innerStringPointer + 1);
				if (
					$pointerAfterInnerString !== null
					&& $tokens[$pointerAfterInnerString]['code'] === T_OPEN_PARENTHESIS
				) {
					return;
				}
			}
		}

		$lineStart = $this->getLineStart($phpcsFile, $parenthesisOpenerPointer);
		$call = $this->getCall($phpcsFile, $parenthesisOpenerPointer, $parenthesisCloserPointer);
		$lineEnd = $this->getLineEnd($phpcsFile, $parenthesisCloserPointer);

		$lineLength = strlen($lineStart . $call . $lineEnd);

		if (!$this->shouldReportError($lineLength)) {
			return;
		}

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $stringPointer - 1);

		$name = ltrim($tokens[$stringPointer]['content'], '\\');

		if (in_array($tokens[$previousPointer]['code'], [T_OBJECT_OPERATOR, T_DOUBLE_COLON], true)) {
			$error = sprintf('Call of method %s() should be placed on a single line.', $name);
		} elseif ($tokens[$previousPointer]['code'] === T_NEW) {
			$error = 'Constructor call should be placed on a single line.';
		} else {
			$error = sprintf('Call of function %s() should be placed on a single line.', $name);
		}

		$fix = $phpcsFile->addFixableError($error, $stringPointer, self::CODE_REQUIRED_SINGLE_LINE_CALL);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->addContent($parenthesisOpenerPointer, $call);

		for ($i = $parenthesisOpenerPointer + 1; $i < $parenthesisCloserPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	private function shouldBeSkipped(File $phpcsFile, int $stringPointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		foreach (array_reverse(TokenHelper::findNextAll($phpcsFile, [T_OPEN_PARENTHESIS, T_FUNCTION], 0, $stringPointer)) as $pointer) {
			if ($tokens[$pointer]['code'] === T_FUNCTION) {
				if ($tokens[$pointer]['scope_closer'] > $stringPointer) {
					return false;
				}

				continue;
			}

			if ($tokens[$pointer]['parenthesis_closer'] < $stringPointer) {
				continue;
			}

			$pointerBeforeParenthesisOpener = TokenHelper::findPreviousEffective($phpcsFile, $pointer - 1);
			if (
				$pointerBeforeParenthesisOpener === null
				|| $tokens[$pointerBeforeParenthesisOpener]['code'] !== T_STRING
			) {
				continue;
			}

			return true;
		}

		return false;
	}

	private function shouldReportError(int $lineLength): bool
	{
		$maxLineLength = SniffSettingsHelper::normalizeInteger($this->maxLineLength);

		if ($maxLineLength === 0) {
			return true;
		}

		return $lineLength <= $maxLineLength;
	}

}
