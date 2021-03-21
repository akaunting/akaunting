<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IndentationHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function preg_replace;
use function rtrim;
use function sprintf;
use function trim;
use const T_ELSEIF;
use const T_IF;
use const T_OPEN_CURLY_BRACKET;
use const T_WHILE;

abstract class AbstractLineCondition implements Sniff
{

	protected const IF_CONTROL_STRUCTURE = 'if';
	protected const WHILE_CONTROL_STRUCTURE = 'while';
	protected const DO_CONTROL_STRUCTURE = 'do';

	/** @var string[] */
	public $checkedControlStructures = [
		self::IF_CONTROL_STRUCTURE,
		self::WHILE_CONTROL_STRUCTURE,
		self::DO_CONTROL_STRUCTURE,
	];

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		$this->checkedControlStructures = SniffSettingsHelper::normalizeArray($this->checkedControlStructures);

		$register = [];

		if (in_array(self::IF_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
			$register[] = T_IF;
			$register[] = T_ELSEIF;
		}

		if (in_array(self::WHILE_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
			$register[] = T_WHILE;
		}

		if (in_array(self::DO_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
			$register[] = T_WHILE;
		}

		return $register;
	}

	protected function shouldBeSkipped(File $phpcsFile, int $controlStructurePointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$controlStructurePointer]['code'] === T_WHILE) {
			$isPartOfDo = $this->isPartOfDo($phpcsFile, $controlStructurePointer);

			if ($isPartOfDo && !in_array(self::DO_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
				return true;
			}

			if (!$isPartOfDo && !in_array(self::WHILE_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
				return true;
			}
		}

		return false;
	}

	protected function getControlStructureName(File $phpcsFile, int $controlStructurePointer): string
	{
		$tokens = $phpcsFile->getTokens();

		return $tokens[$controlStructurePointer]['code'] === T_WHILE && $this->isPartOfDo($phpcsFile, $controlStructurePointer)
			? 'do-while'
			: $tokens[$controlStructurePointer]['content'];
	}

	protected function isPartOfDo(File $phpcsFile, int $whilePointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$parenthesisCloserPointer = $tokens[$whilePointer]['parenthesis_closer'];
		$pointerAfterParentesisCloser = TokenHelper::findNextEffective($phpcsFile, $parenthesisCloserPointer + 1);

		return $tokens[$pointerAfterParentesisCloser]['code'] !== T_OPEN_CURLY_BRACKET;
	}

	protected function getLineStart(File $phpcsFile, int $pointer): string
	{
		$firstPointerOnLine = TokenHelper::findFirstTokenOnLine($phpcsFile, $pointer);

		return IndentationHelper::convertTabsToSpaces($phpcsFile, TokenHelper::getContent($phpcsFile, $firstPointerOnLine, $pointer));
	}

	protected function getCondition(File $phpcsFile, int $parenthesisOpenerPointer, int $parenthesisCloserPointer): string
	{
		$condition = TokenHelper::getContent($phpcsFile, $parenthesisOpenerPointer + 1, $parenthesisCloserPointer - 1);

		return trim(preg_replace(sprintf('~%s[ \t]*~', $phpcsFile->eolChar), ' ', $condition));
	}

	protected function getLineEnd(File $phpcsFile, int $pointer): string
	{
		$firstPointerOnNextLine = TokenHelper::findFirstTokenOnNextLine($phpcsFile, $pointer);

		return rtrim(TokenHelper::getContent($phpcsFile, $pointer, $firstPointerOnNextLine - 1));
	}

}
