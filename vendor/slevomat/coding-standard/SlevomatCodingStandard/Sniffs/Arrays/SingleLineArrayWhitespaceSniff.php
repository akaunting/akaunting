<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use function str_repeat;
use const T_COMMA;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;
use const T_WHITESPACE;

class SingleLineArrayWhitespaceSniff implements Sniff
{

	public const CODE_SPACE_BEFORE_COMMA = 'SpaceBeforeComma';
	public const CODE_SPACE_AFTER_COMMA = 'SpaceAfterComma';
	public const CODE_SPACE_AFTER_ARRAY_OPEN = 'SpaceAfterArrayOpen';
	public const CODE_SPACE_BEFORE_ARRAY_CLOSE = 'SpaceBeforeArrayClose';
	public const CODE_SPACE_IN_EMPTY_ARRAY = 'SpaceInEmptyArray';

	/** @var int */
	public $spacesAroundBrackets = 0;

	/** @var bool */
	public $enableEmptyArrayCheck = false;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_OPEN_SHORT_ARRAY];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $stackPointer
	 */
	public function process(File $phpcsFile, $stackPointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$arrayStart = $stackPointer;
		$arrayEnd = $tokens[$stackPointer]['bracket_closer'];

		// Check only single-line arrays.
		if ($tokens[$arrayStart]['line'] !== $tokens[$arrayEnd]['line']) {
			return $arrayEnd;
		}

		$content = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $arrayStart + 1, $arrayEnd + 1);
		if ($content === $arrayEnd) {
			// Empty array, but if the brackets aren't together, there's a problem.
			if ($this->enableEmptyArrayCheck) {
				$this->checkWhitespaceInEmptyArray($phpcsFile, $arrayStart, $arrayEnd);
			}

			// We can return here because there is nothing else to check.
			// All code below can assume that the array is not empty.
			return $arrayEnd + 1;
		}

		$this->checkWhitespaceAfterOpeningBracket($phpcsFile, $arrayStart);
		$this->checkWhitespaceBeforeClosingBracket($phpcsFile, $arrayEnd);

		for ($i = $arrayStart + 1; $i < $arrayEnd; $i++) {
			// Skip bracketed statements, like function calls.
			if ($tokens[$i]['code'] === T_OPEN_PARENTHESIS) {
				$i = $tokens[$i]['parenthesis_closer'];

				continue;
			}

			// Skip nested arrays as they will be processed separately
			if ($tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
				$i = $tokens[$i]['bracket_closer'];

				continue;
			}

			if ($tokens[$i]['code'] !== T_COMMA) {
				continue;
			}

			// Before checking this comma, make sure we are not at the end of the array.
			$next = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $i + 1, $arrayEnd);
			if ($next === null) {
				return $arrayStart + 1;
			}

			$this->checkWhitespaceBeforeComma($phpcsFile, $i);
			$this->checkWhitespaceAfterComma($phpcsFile, $i);
		}

		return $arrayStart + 1;
	}

	protected function getSpacesAroundBrackets(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->spacesAroundBrackets);
	}

	private function checkWhitespaceInEmptyArray(File $phpcsFile, int $arrayStart, int $arrayEnd): void
	{
		if ($arrayEnd - $arrayStart === 1) {
			return;
		}

		$error = 'Empty array declaration must have no space between the parentheses.';
		$fix = $phpcsFile->addFixableError($error, $arrayStart, self::CODE_SPACE_IN_EMPTY_ARRAY);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->replaceToken($arrayStart + 1, '');
	}

	private function checkWhitespaceAfterOpeningBracket(File $phpcsFile, int $arrayStart): void
	{
		$tokens = $phpcsFile->getTokens();

		$whitespacePointer = $arrayStart + 1;

		$spaceLength = 0;
		if ($tokens[$whitespacePointer]['code'] === T_WHITESPACE) {
			$spaceLength = $tokens[$whitespacePointer]['length'];
		}

		$spacesAroundBrackets = $this->getSpacesAroundBrackets();
		if ($spaceLength === $spacesAroundBrackets) {
			return;
		}

		$error = sprintf('Expected %d spaces after array opening bracket, %d found.', $spacesAroundBrackets, $spaceLength);
		$fix = $phpcsFile->addFixableError($error, $arrayStart, self::CODE_SPACE_AFTER_ARRAY_OPEN);
		if (!$fix) {
			return;
		}

		if ($spaceLength === 0) {
			$phpcsFile->fixer->addContent($arrayStart, str_repeat(' ', $spacesAroundBrackets));
		} else {
			$phpcsFile->fixer->replaceToken($whitespacePointer, str_repeat(' ', $spacesAroundBrackets));
		}
	}

	private function checkWhitespaceBeforeClosingBracket(File $phpcsFile, int $arrayEnd): void
	{
		$tokens = $phpcsFile->getTokens();

		$whitespacePointer = $arrayEnd - 1;

		$spaceLength = 0;
		if ($tokens[$whitespacePointer]['code'] === T_WHITESPACE) {
			$spaceLength = $tokens[$whitespacePointer]['length'];
		}

		$spacesAroundBrackets = $this->getSpacesAroundBrackets();
		if ($spaceLength === $spacesAroundBrackets) {
			return;
		}

		$error = sprintf('Expected %d spaces before array closing bracket, %d found.', $spacesAroundBrackets, $spaceLength);
		$fix = $phpcsFile->addFixableError($error, $arrayEnd, self::CODE_SPACE_BEFORE_ARRAY_CLOSE);
		if (!$fix) {
			return;
		}

		if ($spaceLength === 0) {
			$phpcsFile->fixer->addContentBefore($arrayEnd, str_repeat(' ', $spacesAroundBrackets));
		} else {
			$phpcsFile->fixer->replaceToken($whitespacePointer, str_repeat(' ', $spacesAroundBrackets));
		}
	}

	private function checkWhitespaceBeforeComma(File $phpcsFile, int $comma): void
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$comma - 1]['code'] !== T_WHITESPACE) {
			return;
		}

		if ($tokens[$comma - 2]['code'] === T_COMMA) {
			return;
		}

		$error = sprintf(
			'Expected 0 spaces between "%s" and comma, %d found.',
			$tokens[$comma - 2]['content'],
			$tokens[$comma - 1]['length']
		);
		$fix = $phpcsFile->addFixableError($error, $comma, self::CODE_SPACE_BEFORE_COMMA);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->replaceToken($comma - 1, '');
	}

	private function checkWhitespaceAfterComma(File $phpcsFile, int $comma): void
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$comma + 1]['code'] !== T_WHITESPACE) {
			$error = sprintf('Expected 1 space between comma and "%s", 0 found.', $tokens[$comma + 1]['content']);
			$fix = $phpcsFile->addFixableError($error, $comma, self::CODE_SPACE_AFTER_COMMA);
			if ($fix) {
				$phpcsFile->fixer->addContent($comma, ' ');
			}

			return;
		}

		$spaceLength = $tokens[$comma + 1]['length'];
		if ($spaceLength === 1) {
			return;
		}

		$error = sprintf('Expected 1 space between comma and "%s", %d found.', $tokens[$comma + 2]['content'], $spaceLength);
		$fix = $phpcsFile->addFixableError($error, $comma, self::CODE_SPACE_AFTER_COMMA);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->replaceToken($comma + 1, ' ');
	}

}
