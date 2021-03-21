<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_INTERFACE;
use const T_TRAIT;
use const T_WHITESPACE;

class EmptyLinesAroundClassBracesSniff implements Sniff
{

	public const CODE_NO_EMPTY_LINE_AFTER_OPENING_BRACE = 'NoEmptyLineAfterOpeningBrace';

	public const CODE_MULTIPLE_EMPTY_LINES_AFTER_OPENING_BRACE = 'MultipleEmptyLinesAfterOpeningBrace';

	public const CODE_INCORRECT_EMPTY_LINES_AFTER_OPENING_BRACE = 'IncorrectEmptyLinesAfterOpeningBrace';

	public const CODE_NO_EMPTY_LINE_BEFORE_CLOSING_BRACE = 'NoEmptyLineBeforeClosingBrace';

	public const CODE_MULTIPLE_EMPTY_LINES_BEFORE_CLOSING_BRACE = 'MultipleEmptyLinesBeforeClosingBrace';

	public const CODE_INCORRECT_EMPTY_LINES_BEFORE_CLOSING_BRACE = 'IncorrectEmptyLinesBeforeClosingBrace';

	/** @var int */
	public $linesCountAfterOpeningBrace = 1;

	/** @var int */
	public $linesCountBeforeClosingBrace = 1;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLASS,
			T_ANON_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $stackPointer
	 */
	public function process(File $phpcsFile, $stackPointer): void
	{
		$this->processOpeningBrace($phpcsFile, $stackPointer);
		$this->processClosingBrace($phpcsFile, $stackPointer);
	}

	private function processOpeningBrace(File $phpcsFile, int $stackPointer): void
	{
		$tokens = $phpcsFile->getTokens();
		$typeToken = $tokens[$stackPointer];
		$openerPointer = $typeToken['scope_opener'];
		$openerToken = $tokens[$openerPointer];
		$nextPointerAfterOpeningBrace = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $openerPointer + 1);
		$nextTokenAfterOpeningBrace = $tokens[$nextPointerAfterOpeningBrace];
		$linesCountAfterOpeningBrace = SniffSettingsHelper::normalizeInteger($this->linesCountAfterOpeningBrace);
		$lines = $nextTokenAfterOpeningBrace['line'] - $openerToken['line'] - 1;

		if ($lines === $linesCountAfterOpeningBrace) {
			return;
		}

		if ($linesCountAfterOpeningBrace === 1) {
			$fix = $phpcsFile->addFixableError(
				sprintf('There must be one empty line after %s opening brace.', $typeToken['content']),
				$openerPointer,
				$lines === 0 ? self::CODE_NO_EMPTY_LINE_AFTER_OPENING_BRACE : self::CODE_MULTIPLE_EMPTY_LINES_AFTER_OPENING_BRACE
			);
		} else {
			$fix = $phpcsFile->addFixableError(sprintf(
				'There must be exactly %d empty lines after %s opening brace.',
				$linesCountAfterOpeningBrace,
				$typeToken['content']
			), $openerPointer, self::CODE_INCORRECT_EMPTY_LINES_AFTER_OPENING_BRACE);
		}

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		if ($lines < $linesCountAfterOpeningBrace) {
			for ($i = $lines; $i < $linesCountAfterOpeningBrace; $i++) {
				$phpcsFile->fixer->addNewline($openerPointer);
			}
		} else {
			for ($i = $openerPointer + $linesCountAfterOpeningBrace + 2; $i < $nextPointerAfterOpeningBrace; $i++) {
				if ($tokens[$i]['content'] !== $phpcsFile->eolChar) {
					break;
				}
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		$phpcsFile->fixer->endChangeset();
	}

	private function processClosingBrace(File $phpcsFile, int $stackPointer): void
	{
		$tokens = $phpcsFile->getTokens();
		$typeToken = $tokens[$stackPointer];
		$closerPointer = $typeToken['scope_closer'];
		$closerToken = $tokens[$closerPointer];
		$previousPointerBeforeClosingBrace = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $closerPointer - 1);
		$previousTokenBeforeClosingBrace = $tokens[$previousPointerBeforeClosingBrace];
		$linesCountBeforeClosingBrace = SniffSettingsHelper::normalizeInteger($this->linesCountBeforeClosingBrace);
		$lines = $closerToken['line'] - $previousTokenBeforeClosingBrace['line'] - 1;

		if ($lines === $linesCountBeforeClosingBrace) {
			return;
		}

		if ($linesCountBeforeClosingBrace === 1) {
			$fix = $phpcsFile->addFixableError(
				sprintf('There must be one empty line before %s closing brace.', $typeToken['content']),
				$closerPointer,
				$lines === 0 ? self::CODE_NO_EMPTY_LINE_BEFORE_CLOSING_BRACE : self::CODE_MULTIPLE_EMPTY_LINES_BEFORE_CLOSING_BRACE
			);
		} else {
			$fix = $phpcsFile->addFixableError(sprintf(
				'There must be exactly %d empty lines before %s closing brace.',
				$linesCountBeforeClosingBrace,
				$typeToken['content']
			), $closerPointer, self::CODE_INCORRECT_EMPTY_LINES_BEFORE_CLOSING_BRACE);
		}

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		if ($lines < $linesCountBeforeClosingBrace) {
			for ($i = $lines; $i < $linesCountBeforeClosingBrace; $i++) {
				$phpcsFile->fixer->addNewlineBefore($closerPointer);
			}
		} else {
			for ($i = $previousPointerBeforeClosingBrace + $linesCountBeforeClosingBrace + 2; $i < $closerPointer; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		$phpcsFile->fixer->endChangeset();
	}

}
