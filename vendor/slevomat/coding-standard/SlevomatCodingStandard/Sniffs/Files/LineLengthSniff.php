<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use function in_array;
use function is_int;
use function ltrim;
use function sprintf;
use function strlen;
use function strrpos;
use const T_COMMENT;
use const T_DOC_COMMENT_STRING;
use const T_OPEN_TAG;

class LineLengthSniff implements Sniff
{

	public const CODE_LINE_TOO_LONG = 'LineTooLong';

	/**
	 * The limit that the length of a line must not exceed.
	 *
	 * @var int
	 */
	public $lineLengthLimit = 120;

	/**
	 * Whether or not to ignore comment lines.
	 *
	 * @var bool
	 */
	public $ignoreComments = false;

	/**
	 * Whether or not to ignore import lines (use).
	 *
	 * @var bool
	 */
	public $ignoreImports = true;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_OPEN_TAG];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
	 * @param File $phpcsFile
	 * @param int $pointer
	 */
	public function process(File $phpcsFile, $pointer): int
	{
		$tokens = $phpcsFile->getTokens();
		for ($i = 0; $i < $phpcsFile->numTokens; $i++) {
			if ($tokens[$i]['column'] !== 1) {
				continue;
			}

			$this->checkLineLength($phpcsFile, $i);
		}

		return $phpcsFile->numTokens + 1;
	}

	private function checkLineLength(File $phpcsFile, int $pointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$pointer]['column'] === 1 && $tokens[$pointer]['length'] === 0) {
			// Blank line.
			return;
		}

		$line = $tokens[$pointer]['line'];
		$nextLineStartPtr = $pointer;
		while (isset($tokens[$nextLineStartPtr]) && $line === $tokens[$nextLineStartPtr]['line']) {
			$pointer = $nextLineStartPtr;
			$nextLineStartPtr++;
		}

		if ($tokens[$pointer]['content'] === $phpcsFile->eolChar) {
			$pointer--;
		}

		$lineLength = $tokens[$pointer]['column'] + $tokens[$pointer]['length'] - 1;
		if ($lineLength <= $this->lineLengthLimit) {
			return;
		}

		if (in_array($tokens[$pointer]['code'], [T_COMMENT, T_DOC_COMMENT_STRING], true)) {
			if ($this->ignoreComments === true) {
				return;
			}

			// If this is a long comment, check if it can be broken up onto multiple lines.
			// Some comments contain unbreakable strings like URLs and so it makes sense
			// to ignore the line length in these cases if the URL would be longer than the max
			// line length once you indent it to the correct level.
			if ($lineLength > $this->lineLengthLimit) {
				$oldLength = strlen($tokens[$pointer]['content']);
				$newLength = strlen(ltrim($tokens[$pointer]['content'], "/#\t "));
				$indent = $tokens[$pointer]['column'] - 1 + $oldLength - $newLength;

				$nonBreakingLength = $tokens[$pointer]['length'];

				$space = strrpos($tokens[$pointer]['content'], ' ');
				if ($space !== false) {
					$nonBreakingLength -= $space + 1;
				}

				if ($nonBreakingLength + $indent > $this->lineLengthLimit) {
					return;
				}
			}
		}

		if ($this->ignoreImports) {
			$usePointer = UseStatementHelper::getUseStatementPointer($phpcsFile, $pointer - 1);
			if (is_int($usePointer)
				&& $tokens[$usePointer]['line'] === $tokens[$pointer]['line']
				&& !UseStatementHelper::isTraitUse($phpcsFile, $usePointer)) {
				return;
			}
		}

		$error = sprintf('Line exceeds maximum limit of %s characters, contains %s characters.', $this->lineLengthLimit, $lineLength);
		$phpcsFile->addError($error, $pointer, self::CODE_LINE_TOO_LONG);
	}

}
