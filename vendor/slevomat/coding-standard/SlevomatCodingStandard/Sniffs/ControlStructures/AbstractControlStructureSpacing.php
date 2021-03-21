<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use Exception;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\CommentHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Sniffs\Namespaces\UndefinedKeywordTokenException;
use Throwable;
use function array_key_exists;
use function array_map;
use function array_values;
use function constant;
use function count;
use function defined;
use function in_array;
use function sprintf;
use function strlen;
use function substr;
use function substr_count;
use const T_ANON_CLASS;
use const T_CASE;
use const T_CATCH;
use const T_CLOSE_CURLY_BRACKET;
use const T_CLOSURE;
use const T_COLON;
use const T_DEFAULT;
use const T_DO;
use const T_ELSE;
use const T_ELSEIF;
use const T_FINALLY;
use const T_FN;
use const T_FOR;
use const T_FOREACH;
use const T_IF;
use const T_OPEN_CURLY_BRACKET;
use const T_OPEN_SHORT_ARRAY;
use const T_OPEN_TAG;
use const T_SEMICOLON;
use const T_SWITCH;
use const T_TRY;
use const T_WHILE;
use const T_WHITESPACE;

/**
 * @internal
 */
abstract class AbstractControlStructureSpacing implements Sniff
{

	public const CODE_INCORRECT_LINES_COUNT_BEFORE_CONTROL_STRUCTURE = 'IncorrectLinesCountBeforeControlStructure';
	public const CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_CONTROL_STRUCTURE = 'IncorrectLinesCountBeforeFirstControlStructure';
	public const CODE_INCORRECT_LINES_COUNT_AFTER_CONTROL_STRUCTURE = 'IncorrectLinesCountAfterControlStructure';
	public const CODE_INCORRECT_LINES_COUNT_AFTER_LAST_CONTROL_STRUCTURE = 'IncorrectLinesCountAfterLastControlStructure';

	/** @var (string|int)[]|null */
	private $normalizedTokensToCheck;

	/**
	 * @return array<int|string>
	 */
	abstract protected function getSupportedTokens(): array;

	/**
	 * @return string[]
	 */
	abstract protected function getTokensToCheck(): array;

	abstract protected function getLinesCountBefore(): int;

	abstract protected function getLinesCountBeforeFirst(File $phpcsFile, int $controlStructurePointer): int;

	abstract protected function getLinesCountAfter(): int;

	abstract protected function getLinesCountAfterLast(File $phpcsFile, int $controlStructurePointer, int $controlStructureEndPointer): int;

	/**
	 * @return (int|string)[]
	 */
	public function register(): array
	{
		return $this->getNormalizedTokensToCheck();
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $controlStructurePointer
	 */
	public function process(File $phpcsFile, $controlStructurePointer): void
	{
		$this->checkLinesBefore($phpcsFile, $controlStructurePointer);

		try {
			$this->checkLinesAfter($phpcsFile, $controlStructurePointer);
		} catch (Throwable $e) {
			// Unsupported syntax without curly braces.
			return;
		}
	}

	protected function checkLinesBefore(File $phpcsFile, int $controlStructurePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$nonWhitespacePointerBefore = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $controlStructurePointer - 1);

		$controlStructureStartPointer = $controlStructurePointer;
		$pointerBefore = $nonWhitespacePointerBefore;

		$pointerToCheckFirst = $pointerBefore;

		if (in_array($tokens[$nonWhitespacePointerBefore]['code'], Tokens::$commentTokens, true)) {
			$effectivePointerBefore = TokenHelper::findPreviousEffective($phpcsFile, $pointerBefore - 1);

			if ($tokens[$effectivePointerBefore]['line'] === $tokens[$nonWhitespacePointerBefore]['line']) {
				$pointerToCheckFirst = $effectivePointerBefore;
			} elseif ($tokens[$nonWhitespacePointerBefore]['line'] + 1 === $tokens[$controlStructurePointer]['line']) {
				if ($tokens[$effectivePointerBefore]['line'] !== $tokens[$nonWhitespacePointerBefore]['line']) {
					$controlStructureStartPointer = array_key_exists('comment_opener', $tokens[$nonWhitespacePointerBefore])
						? $tokens[$nonWhitespacePointerBefore]['comment_opener']
						: CommentHelper::getMultilineCommentStartPointer($phpcsFile, $nonWhitespacePointerBefore);
					$pointerBefore = TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $controlStructureStartPointer - 1);
				}
				$pointerToCheckFirst = $pointerBefore;
			}
		}

		$isFirstControlStructure = in_array($tokens[$pointerToCheckFirst]['code'], [T_OPEN_CURLY_BRACKET, T_COLON], true);

		if (
			$isFirstControlStructure
			&& in_array($tokens[$controlStructurePointer]['code'], [T_CASE, T_DEFAULT], true)
			&& array_key_exists('scope_condition', $tokens[$pointerBefore])
			&& in_array($tokens[$tokens[$pointerBefore]['scope_condition']]['code'], [T_CASE, T_DEFAULT], true)
		) {
			$isFirstControlStructure = false;
		}

		$whitespaceBefore = '';

		if ($tokens[$pointerBefore]['code'] === T_OPEN_TAG) {
			$whitespaceBefore .= substr($tokens[$pointerBefore]['content'], strlen('<?php'));
		}

		$hasCommentWithLineEndBefore = in_array($tokens[$pointerBefore]['code'], TokenHelper::$inlineCommentTokenCodes, true)
			&& substr($tokens[$pointerBefore]['content'], -strlen($phpcsFile->eolChar)) === $phpcsFile->eolChar;
		if ($hasCommentWithLineEndBefore) {
			$whitespaceBefore .= $phpcsFile->eolChar;
		}

		if ($pointerBefore + 1 !== $controlStructurePointer) {
			$whitespaceBefore .= TokenHelper::getContent($phpcsFile, $pointerBefore + 1, $controlStructureStartPointer - 1);
		}

		$requiredLinesCountBefore = $isFirstControlStructure
			? $this->getLinesCountBeforeFirst($phpcsFile, $controlStructurePointer)
			: $this->getLinesCountBefore();
		$actualLinesCountBefore = substr_count($whitespaceBefore, $phpcsFile->eolChar) - 1;

		if ($requiredLinesCountBefore === $actualLinesCountBefore) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d lines before "%s", found %d.',
				$requiredLinesCountBefore,
				$tokens[$controlStructurePointer]['content'],
				$actualLinesCountBefore
			),
			$controlStructurePointer,
			$isFirstControlStructure ? self::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_CONTROL_STRUCTURE : self::CODE_INCORRECT_LINES_COUNT_BEFORE_CONTROL_STRUCTURE
		);

		if (!$fix) {
			return;
		}

		$endOfLineBeforePointer = TokenHelper::findPreviousContent(
			$phpcsFile,
			T_WHITESPACE,
			$phpcsFile->eolChar,
			$controlStructureStartPointer - 1
		);

		$phpcsFile->fixer->beginChangeset();

		if ($tokens[$pointerBefore]['code'] === T_OPEN_TAG) {
			$phpcsFile->fixer->replaceToken($pointerBefore, '<?php');
		}

		for ($i = $pointerBefore + 1; $i <= $endOfLineBeforePointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$linesToAdd = $hasCommentWithLineEndBefore ? $requiredLinesCountBefore - 1 : $requiredLinesCountBefore;
		for ($i = 0; $i <= $linesToAdd; $i++) {
			$phpcsFile->fixer->addNewline($pointerBefore);
		}

		$phpcsFile->fixer->endChangeset();
	}

	protected function checkLinesAfter(File $phpcsFile, int $controlStructurePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$controlStructureEndPointer = $this->findControlStructureEnd($phpcsFile, $controlStructurePointer);

		$pointerAfterControlStructureEnd = TokenHelper::findNextEffective($phpcsFile, $controlStructureEndPointer + 1);
		if (
			$pointerAfterControlStructureEnd !== null
			&& $tokens[$pointerAfterControlStructureEnd]['code'] === T_SEMICOLON
		) {
			$controlStructureEndPointer = $pointerAfterControlStructureEnd;
		}

		$notWhitespacePointerAfter = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $controlStructureEndPointer + 1);

		if ($notWhitespacePointerAfter === null) {
			return;
		}

		$hasCommentAfter = in_array($tokens[$notWhitespacePointerAfter]['code'], Tokens::$commentTokens, true);
		$isCommentAfterOnSameLine = false;
		$pointerAfter = $notWhitespacePointerAfter;

		$isControlStructureEndAfterPointer = static function (int $pointer) use ($tokens, $controlStructurePointer): bool {
			return in_array($tokens[$controlStructurePointer]['code'], [T_CASE, T_DEFAULT], true)
				? $tokens[$pointer]['code'] === T_CLOSE_CURLY_BRACKET
				: in_array($tokens[$pointer]['code'], [T_CLOSE_CURLY_BRACKET, T_CASE, T_DEFAULT], true);
		};

		if ($hasCommentAfter) {
			if ($tokens[$notWhitespacePointerAfter]['line'] === $tokens[$controlStructureEndPointer]['line'] + 1) {
				$commentEndPointer = CommentHelper::getCommentEndPointer($phpcsFile, $notWhitespacePointerAfter);
				$pointerAfterComment = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $commentEndPointer + 1);

				if ($isControlStructureEndAfterPointer($pointerAfterComment)) {
					$controlStructureEndPointer = $commentEndPointer;
					$pointerAfter = $pointerAfterComment;
				}
			} elseif ($tokens[$notWhitespacePointerAfter]['line'] === $tokens[$controlStructureEndPointer]['line']) {
				$isCommentAfterOnSameLine = true;
				$pointerAfter = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $notWhitespacePointerAfter + 1);
			}
		}

		$isLastControlStructure = $isControlStructureEndAfterPointer($pointerAfter);

		$requiredLinesCountAfter = $isLastControlStructure
			? $this->getLinesCountAfterLast($phpcsFile, $controlStructurePointer, $controlStructureEndPointer)
			: $this->getLinesCountAfter();
		$actualLinesCountAfter = $tokens[$pointerAfter]['line'] - $tokens[$controlStructureEndPointer]['line'] - 1;

		if ($requiredLinesCountAfter === $actualLinesCountAfter) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d lines after "%s", found %d.',
				$requiredLinesCountAfter,
				$tokens[$controlStructurePointer]['content'],
				$actualLinesCountAfter
			),
			$controlStructurePointer,
			$isLastControlStructure ? self::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_CONTROL_STRUCTURE : self::CODE_INCORRECT_LINES_COUNT_AFTER_CONTROL_STRUCTURE
		);

		if (!$fix) {
			return;
		}

		$replaceStartPointer = $isCommentAfterOnSameLine ? $notWhitespacePointerAfter : $controlStructureEndPointer;
		$endOfLineBeforeAfterPointer = TokenHelper::findPreviousContent($phpcsFile, T_WHITESPACE, $phpcsFile->eolChar, $pointerAfter - 1);

		$phpcsFile->fixer->beginChangeset();

		for ($i = $replaceStartPointer + 1; $i <= $endOfLineBeforeAfterPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		if ($isCommentAfterOnSameLine) {
			for ($i = 0; $i < $requiredLinesCountAfter; $i++) {
				$phpcsFile->fixer->addNewline($notWhitespacePointerAfter);
			}
		} else {
			for ($i = 0; $i <= $requiredLinesCountAfter; $i++) {
				$phpcsFile->fixer->addNewline($controlStructureEndPointer);
			}
		}

		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @return (int|string)[]
	 */
	private function getNormalizedTokensToCheck(): array
	{
		if ($this->normalizedTokensToCheck === null) {
			$supportedTokens = $this->getSupportedTokens();

			$this->normalizedTokensToCheck = array_values(array_map(
				static function (string $tokenCode) use ($supportedTokens) {
					if (!defined($tokenCode)) {
						throw new UndefinedKeywordTokenException($tokenCode);
					}

					$const = constant($tokenCode);
					if (!in_array($const, $supportedTokens, true)) {
						throw new UnsupportedTokenException($tokenCode);
					}

					return $const;
				},
				SniffSettingsHelper::normalizeArray($this->getTokensToCheck())
			));

			if (count($this->normalizedTokensToCheck) === 0) {
				$this->normalizedTokensToCheck = $supportedTokens;
			}
		}

		return $this->normalizedTokensToCheck;
	}

	private function findControlStructureEnd(File $phpcsFile, int $controlStructurePointer): int
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$controlStructurePointer]['code'] === T_IF) {
			if (!array_key_exists('scope_closer', $tokens[$controlStructurePointer])) {
				throw new Exception('"if" without curly braces is not supported.');
			}

			$pointerAfterParenthesisCloser = TokenHelper::findNextEffective(
				$phpcsFile,
				$tokens[$controlStructurePointer]['parenthesis_closer'] + 1
			);
			if ($pointerAfterParenthesisCloser !== null && $tokens[$pointerAfterParenthesisCloser]['code'] === T_COLON) {
				throw new Exception('"if" without curly braces is not supported.');
			}

			$controlStructureEndPointer = $tokens[$controlStructurePointer]['scope_closer'];
			do {
				$nextPointer = TokenHelper::findNextEffective($phpcsFile, $controlStructureEndPointer + 1);
				if ($nextPointer === null) {
					return $controlStructureEndPointer;
				}

				if ($tokens[$nextPointer]['code'] === T_ELSE) {
					if (!array_key_exists('scope_closer', $tokens[$nextPointer])) {
						throw new Exception('"else" without curly braces is not supported.');
					}

					return $tokens[$nextPointer]['scope_closer'];
				}

				if ($tokens[$nextPointer]['code'] !== T_ELSEIF) {
					return $controlStructureEndPointer;
				}

				$controlStructureEndPointer = $tokens[$nextPointer]['scope_closer'];
			} while (true);
		}

		if ($tokens[$controlStructurePointer]['code'] === T_DO) {
			$whilePointer = TokenHelper::findNext($phpcsFile, T_WHILE, $tokens[$controlStructurePointer]['scope_closer'] + 1);
			return (int) TokenHelper::findNext($phpcsFile, T_SEMICOLON, $tokens[$whilePointer]['parenthesis_closer'] + 1);
		}

		if ($tokens[$controlStructurePointer]['code'] === T_TRY) {
			$controlStructureEndPointer = $tokens[$controlStructurePointer]['scope_closer'];
			do {
				$nextPointer = TokenHelper::findNextEffective($phpcsFile, $controlStructureEndPointer + 1);

				if ($nextPointer === null) {
					return $controlStructureEndPointer;
				}

				if (!in_array($tokens[$nextPointer]['code'], [T_CATCH, T_FINALLY], true)) {
					return $controlStructureEndPointer;
				}

				$controlStructureEndPointer = $tokens[$nextPointer]['scope_closer'];
			} while (true);
		}

		if (in_array($tokens[$controlStructurePointer]['code'], [T_WHILE, T_FOR, T_FOREACH, T_SWITCH], true)) {
			return $tokens[$controlStructurePointer]['scope_closer'];
		}

		if (in_array($tokens[$controlStructurePointer]['code'], [T_CASE, T_DEFAULT], true)) {
			$switchPointer = TokenHelper::findPrevious($phpcsFile, T_SWITCH, $controlStructurePointer - 1);

			$pointers = TokenHelper::findNextAll(
				$phpcsFile,
				[T_CASE, T_DEFAULT],
				$controlStructurePointer + 1,
				$tokens[$switchPointer]['scope_closer']
			);

			foreach ($pointers as $pointer) {
				if (TokenHelper::findPrevious($phpcsFile, T_SWITCH, $pointer - 1) === $switchPointer) {
					return TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $pointer - 1);
				}
			}

			return TokenHelper::findPreviousExcluding($phpcsFile, T_WHITESPACE, $tokens[$switchPointer]['scope_closer'] - 1);
		}

		$nextPointer = TokenHelper::findNext(
			$phpcsFile,
			[T_SEMICOLON, T_ANON_CLASS, T_CLOSURE, T_FN, T_OPEN_SHORT_ARRAY],
			$controlStructurePointer + 1
		);
		if ($tokens[$nextPointer]['code'] === T_SEMICOLON) {
			return $nextPointer;
		}

		$nextPointer = $tokens[$nextPointer]['code'] === T_OPEN_SHORT_ARRAY
			? (int) TokenHelper::findNext($phpcsFile, T_SEMICOLON, $tokens[$nextPointer]['bracket_closer'] + 1)
			: (int) TokenHelper::findNext($phpcsFile, T_SEMICOLON, $tokens[$nextPointer]['scope_closer'] + 1);

		$level = $tokens[$controlStructurePointer]['level'];
		while ($level !== $tokens[$nextPointer]['level']) {
			$nextPointer = (int) TokenHelper::findNext($phpcsFile, T_SEMICOLON, $nextPointer + 1);
		}

		return $nextPointer;
	}

}
