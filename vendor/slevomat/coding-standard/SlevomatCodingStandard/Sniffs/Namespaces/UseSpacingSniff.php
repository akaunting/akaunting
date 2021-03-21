<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\CommentHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\UseStatement;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use function array_key_exists;
use function array_values;
use function count;
use function in_array;
use function sprintf;
use const T_OPEN_TAG;
use const T_SEMICOLON;
use const T_WHITESPACE;

class UseSpacingSniff implements Sniff
{

	public const CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE = 'IncorrectLinesCountBeforeFirstUse';
	public const CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE = 'IncorrectLinesCountBetweenSameTypeOfUse';
	public const CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE = 'IncorrectLinesCountBetweenDifferentTypeOfUse';
	public const CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE = 'IncorrectLinesCountAfterLastUse';

	/** @var int */
	public $linesCountBeforeFirstUse = 1;

	/** @var int */
	public $linesCountBetweenUseTypes = 0;

	/** @var int */
	public $linesCountAfterLastUse = 1;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_OPEN_TAG,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $openTagPointer
	 */
	public function process(File $phpcsFile, $openTagPointer): void
	{
		if (TokenHelper::findPrevious($phpcsFile, T_OPEN_TAG, $openTagPointer - 1) !== null) {
			return;
		}

		$fileUseStatements = UseStatementHelper::getFileUseStatements($phpcsFile);

		if (count($fileUseStatements) === 0) {
			return;
		}

		foreach ($fileUseStatements as $useStatementsByName) {
			$useStatements = array_values($useStatementsByName);

			$this->checkLinesBeforeFirstUse($phpcsFile, $useStatements[0]);
			$this->checkLinesAfterLastUse($phpcsFile, $useStatements[count($useStatements) - 1]);
			$this->checkLinesBetweenSameTypesOfUse($phpcsFile, $useStatements);
			$this->checkLinesBetweenDifferentTypesOfUse($phpcsFile, $useStatements);
		}
	}

	private function checkLinesBeforeFirstUse(File $phpcsFile, UseStatement $firstUse): void
	{
		$tokens = $phpcsFile->getTokens();

		/** @var int $pointerBeforeFirstUse */
		$pointerBeforeFirstUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $firstUse->getPointer() - 1);
		$useStartPointer = $firstUse->getPointer();

		if (
			in_array($tokens[$pointerBeforeFirstUse]['code'], Tokens::$commentTokens, true)
			&& $tokens[$pointerBeforeFirstUse]['line'] + 1 === $tokens[$useStartPointer]['line']
		) {
			$useStartPointer = array_key_exists('comment_opener', $tokens[$pointerBeforeFirstUse])
				? $tokens[$pointerBeforeFirstUse]['comment_opener']
				: CommentHelper::getMultilineCommentStartPointer($phpcsFile, $pointerBeforeFirstUse);
			/** @var int $pointerBeforeFirstUse */
			$pointerBeforeFirstUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $useStartPointer - 1);
		}

		$requiredLinesCountBeforeFirstUse = SniffSettingsHelper::normalizeInteger($this->linesCountBeforeFirstUse);
		$actualLinesCountBeforeFirstUse = $tokens[$useStartPointer]['line'] - $tokens[$pointerBeforeFirstUse]['line'] - 1;

		if ($actualLinesCountBeforeFirstUse === $requiredLinesCountBeforeFirstUse) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d lines before first use statement, found %d.',
				$requiredLinesCountBeforeFirstUse,
				$actualLinesCountBeforeFirstUse
			),
			$firstUse->getPointer(),
			self::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_USE
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		if ($tokens[$pointerBeforeFirstUse]['code'] === T_OPEN_TAG) {
			$phpcsFile->fixer->replaceToken($pointerBeforeFirstUse, '<?php');
		}

		for ($i = $pointerBeforeFirstUse + 1; $i < $useStartPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		for ($i = 0; $i <= $requiredLinesCountBeforeFirstUse; $i++) {
			$phpcsFile->fixer->addNewline($pointerBeforeFirstUse);
		}
		$phpcsFile->fixer->endChangeset();
	}

	private function checkLinesAfterLastUse(File $phpcsFile, UseStatement $lastUse): void
	{
		$tokens = $phpcsFile->getTokens();

		/** @var int $useEndPointer */
		$useEndPointer = TokenHelper::findNextLocal($phpcsFile, T_SEMICOLON, $lastUse->getPointer() + 1);

		$pointerAfterWhitespaceEnd = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $useEndPointer + 1);
		if ($pointerAfterWhitespaceEnd === null) {
			return;
		}

		if (
			in_array($tokens[$pointerAfterWhitespaceEnd]['code'], Tokens::$commentTokens, true)
			&& (
				$tokens[$useEndPointer]['line'] === $tokens[$pointerAfterWhitespaceEnd]['line']
				|| $tokens[$useEndPointer]['line'] + 1 === $tokens[$pointerAfterWhitespaceEnd]['line']
			)
		) {
			$useEndPointer = array_key_exists('comment_closer', $tokens[$pointerAfterWhitespaceEnd])
				? $tokens[$pointerAfterWhitespaceEnd]['comment_closer']
				: CommentHelper::getMultilineCommentEndPointer($phpcsFile, $pointerAfterWhitespaceEnd);
			/** @var int $pointerAfterWhitespaceEnd */
			$pointerAfterWhitespaceEnd = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $useEndPointer + 1);
		}

		$requiredLinesCountAfterLastUse = SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastUse);
		$actualLinesCountAfterLastUse = $tokens[$pointerAfterWhitespaceEnd]['line'] - $tokens[$useEndPointer]['line'] - 1;

		if ($actualLinesCountAfterLastUse === $requiredLinesCountAfterLastUse) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d lines after last use statement, found %d.',
				$requiredLinesCountAfterLastUse,
				$actualLinesCountAfterLastUse
			),
			$lastUse->getPointer(),
			self::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_USE
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		for ($i = $useEndPointer + 1; $i < $pointerAfterWhitespaceEnd; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$linesToAdd = $requiredLinesCountAfterLastUse;
		if (in_array($tokens[$useEndPointer]['code'], TokenHelper::$inlineCommentTokenCodes, true)) {
			$linesToAdd--;
		}

		for ($i = 0; $i <= $linesToAdd; $i++) {
			$phpcsFile->fixer->addNewline($useEndPointer);
		}
		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @param File $phpcsFile
	 * @param UseStatement[] $useStatements
	 */
	private function checkLinesBetweenSameTypesOfUse(File $phpcsFile, array $useStatements): void
	{
		if (count($useStatements) === 1) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$requiredLinesCountBetweenUses = 0;

		$previousUse = null;
		foreach ($useStatements as $use) {
			if ($previousUse === null) {
				$previousUse = $use;
				continue;
			}

			if (!$use->hasSameType($previousUse)) {
				$previousUse = null;
				continue;
			}

			/** @var int $pointerBeforeUse */
			$pointerBeforeUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $use->getPointer() - 1);
			$useStartPointer = $use->getPointer();

			if (
				in_array($tokens[$pointerBeforeUse]['code'], Tokens::$commentTokens, true)
				&& TokenHelper::findFirstNonWhitespaceOnLine($phpcsFile, $pointerBeforeUse) === $pointerBeforeUse
				&& $tokens[$pointerBeforeUse]['line'] + 1 === $tokens[$useStartPointer]['line']
			) {
				$useStartPointer = array_key_exists('comment_opener', $tokens[$pointerBeforeUse])
					? $tokens[$pointerBeforeUse]['comment_opener']
					: CommentHelper::getMultilineCommentStartPointer($phpcsFile, $pointerBeforeUse);
			}

			$actualLinesCountAfterPreviousUse = $tokens[$useStartPointer]['line'] - $tokens[$previousUse->getPointer()]['line'] - 1;

			if ($actualLinesCountAfterPreviousUse === $requiredLinesCountBetweenUses) {
				$previousUse = $use;
				continue;
			}

			$fix = $phpcsFile->addFixableError(
				sprintf(
					'Expected %d lines between same types of use statement, found %d.',
					$requiredLinesCountBetweenUses,
					$actualLinesCountAfterPreviousUse
				),
				$use->getPointer(),
				self::CODE_INCORRECT_LINES_COUNT_BETWEEN_SAME_TYPES_OF_USE
			);

			if (!$fix) {
				$previousUse = $use;
				continue;
			}

			/** @var int $previousUseSemicolonPointer */
			$previousUseSemicolonPointer = TokenHelper::findNextLocal($phpcsFile, T_SEMICOLON, $previousUse->getPointer() + 1);

			$phpcsFile->fixer->beginChangeset();
			for ($i = $previousUseSemicolonPointer + 1; $i < $useStartPointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
			$phpcsFile->fixer->addNewline($previousUseSemicolonPointer);
			$phpcsFile->fixer->endChangeset();

			$previousUse = $use;
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param UseStatement[] $useStatements
	 */
	private function checkLinesBetweenDifferentTypesOfUse(File $phpcsFile, array $useStatements): void
	{
		if (count($useStatements) === 1) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$requiredLinesCountBetweenUseTypes = SniffSettingsHelper::normalizeInteger($this->linesCountBetweenUseTypes);

		$previousUse = null;
		foreach ($useStatements as $use) {
			if ($previousUse === null) {
				$previousUse = $use;
				continue;
			}

			if ($use->hasSameType($previousUse)) {
				$previousUse = $use;
				continue;
			}

			/** @var int $pointerBeforeUse */
			$pointerBeforeUse = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $use->getPointer() - 1);
			$useStartPointer = $use->getPointer();

			if (
				in_array($tokens[$pointerBeforeUse]['code'], Tokens::$commentTokens, true)
				&& TokenHelper::findFirstNonWhitespaceOnLine($phpcsFile, $pointerBeforeUse) === $pointerBeforeUse
				&& $tokens[$pointerBeforeUse]['line'] + 1 === $tokens[$useStartPointer]['line']
			) {
				$useStartPointer = array_key_exists('comment_opener', $tokens[$pointerBeforeUse])
					? $tokens[$pointerBeforeUse]['comment_opener']
					: CommentHelper::getMultilineCommentStartPointer($phpcsFile, $pointerBeforeUse);
			}

			$actualLinesCountAfterPreviousUse = $tokens[$useStartPointer]['line'] - $tokens[$previousUse->getPointer()]['line'] - 1;

			if ($actualLinesCountAfterPreviousUse === $requiredLinesCountBetweenUseTypes) {
				$previousUse = $use;
				continue;
			}

			$fix = $phpcsFile->addFixableError(
				sprintf(
					'Expected %d lines between different types of use statement, found %d.',
					$requiredLinesCountBetweenUseTypes,
					$actualLinesCountAfterPreviousUse
				),
				$use->getPointer(),
				self::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_TYPES_OF_USE
			);

			if (!$fix) {
				$previousUse = $use;
				continue;
			}

			/** @var int $previousUseSemicolonPointer */
			$previousUseSemicolonPointer = TokenHelper::findNextLocal($phpcsFile, T_SEMICOLON, $previousUse->getPointer() + 1);

			$phpcsFile->fixer->beginChangeset();
			for ($i = $previousUseSemicolonPointer + 1; $i < $useStartPointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
			for ($i = 0; $i <= $requiredLinesCountBetweenUseTypes; $i++) {
				$phpcsFile->fixer->addNewline($previousUseSemicolonPointer);
			}
			$phpcsFile->fixer->endChangeset();

			$previousUse = $use;
		}
	}

}
