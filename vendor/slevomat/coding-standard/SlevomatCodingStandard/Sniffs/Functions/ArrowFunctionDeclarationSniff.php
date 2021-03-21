<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function preg_match;
use function sprintf;
use function str_repeat;
use function strlen;
use function strpos;
use const T_FN;
use const T_FN_ARROW;
use const T_WHITESPACE;

class ArrowFunctionDeclarationSniff implements Sniff
{

	public const CODE_INCORRECT_SPACES_AFTER_KEYWORD = 'IncorrectSpacesAfterKeyword';
	public const CODE_INCORRECT_SPACES_BEFORE_ARROW = 'IncorrectSpacesBeforeArrow';
	public const CODE_INCORRECT_SPACES_AFTER_ARROW = 'IncorrectSpacesAfterArrow';

	/** @var int */
	public $spacesCountAfterKeyword = 1;

	/** @var int */
	public $spacesCountBeforeArrow = 1;

	/** @var int */
	public $spacesCountAfterArrow = 1;

	/** @var bool */
	public $allowMultiLine = false;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_FN,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $arrowFunctionPointer
	 */
	public function process(File $phpcsFile, $arrowFunctionPointer): void
	{
		$this->checkSpacesAfterKeyword($phpcsFile, $arrowFunctionPointer);

		$arrowPointer = TokenHelper::findNext($phpcsFile, T_FN_ARROW, $arrowFunctionPointer);

		$this->checkSpacesBeforeArrow($phpcsFile, $arrowPointer);
		$this->checkSpacesAfterArrow($phpcsFile, $arrowPointer);
	}

	private function checkSpacesAfterKeyword(File $phpcsFile, int $arrowFunctionPointer): void
	{
		$pointerAfter = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $arrowFunctionPointer + 1);

		$spaces = TokenHelper::getContent($phpcsFile, $arrowFunctionPointer + 1, $pointerAfter - 1);

		if ($this->allowMultiLine && strpos($spaces, $phpcsFile->eolChar) === 0) {
			return;
		}

		$actualSpaces = strlen($spaces);
		$requiredSpaces = SniffSettingsHelper::normalizeInteger($this->spacesCountAfterKeyword);

		if ($actualSpaces === $requiredSpaces && ($requiredSpaces === 0 || preg_match('~^ +$~', $spaces) === 1)) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			$this->formatErrorMessage('after "fn" keyword', $requiredSpaces),
			$arrowFunctionPointer,
			self::CODE_INCORRECT_SPACES_AFTER_KEYWORD
		);
		if (!$fix) {
			return;
		}

		$this->fixSpaces($phpcsFile, $arrowFunctionPointer, $pointerAfter, $requiredSpaces);
	}

	private function checkSpacesBeforeArrow(File $phpcsFile, int $arrowPointer): void
	{
		$pointerBefore = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $arrowPointer - 1);

		$spaces = TokenHelper::getContent($phpcsFile, $pointerBefore + 1, $arrowPointer - 1);

		if ($this->allowMultiLine && strpos($spaces, $phpcsFile->eolChar) === 0) {
			return;
		}

		$actualSpaces = strlen($spaces);
		$requiredSpaces = SniffSettingsHelper::normalizeInteger($this->spacesCountBeforeArrow);

		if ($actualSpaces === $requiredSpaces && ($requiredSpaces === 0 || preg_match('~^ +$~', $spaces) === 1)) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			$this->formatErrorMessage('before =>', $requiredSpaces),
			$arrowPointer,
			self::CODE_INCORRECT_SPACES_BEFORE_ARROW
		);
		if (!$fix) {
			return;
		}

		$this->fixSpaces($phpcsFile, $pointerBefore, $arrowPointer, $requiredSpaces);
	}

	private function checkSpacesAfterArrow(File $phpcsFile, int $arrowPointer): void
	{
		$pointerAfter = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $arrowPointer + 1);

		$spaces = TokenHelper::getContent($phpcsFile, $arrowPointer + 1, $pointerAfter - 1);

		if ($this->allowMultiLine && strpos($spaces, $phpcsFile->eolChar) === 0) {
			return;
		}

		$actualSpaces = strlen($spaces);
		$requiredSpaces = SniffSettingsHelper::normalizeInteger($this->spacesCountAfterArrow);

		if ($actualSpaces === $requiredSpaces && ($requiredSpaces === 0 || preg_match('~^ +$~', $spaces) === 1)) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			$this->formatErrorMessage('after =>', $requiredSpaces),
			$arrowPointer,
			self::CODE_INCORRECT_SPACES_AFTER_ARROW
		);
		if (!$fix) {
			return;
		}

		$this->fixSpaces($phpcsFile, $arrowPointer, $pointerAfter, $requiredSpaces);
	}

	private function formatErrorMessage(string $suffix, int $requiredSpaces): string
	{
		return $requiredSpaces === 0
			? sprintf('There must be no whitespace %s.', $suffix)
			: sprintf('There must be exactly %d whitespace%s %s.', $requiredSpaces, $requiredSpaces !== 1 ? 's' : '', $suffix);
	}

	private function fixSpaces(File $phpcsFile, int $pointerBefore, int $pointerAfter, int $requiredSpaces): void
	{
		$phpcsFile->fixer->beginChangeset();

		if ($requiredSpaces > 0) {
			$phpcsFile->fixer->addContent($pointerBefore, str_repeat(' ', $requiredSpaces));
		}

		for ($i = $pointerBefore + 1; $i < $pointerAfter; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

}
