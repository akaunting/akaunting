<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use const T_CASE;
use const T_CLOSE_CURLY_BRACKET;
use const T_DEFAULT;
use const T_DO;
use const T_FOR;
use const T_FOREACH;
use const T_IF;
use const T_SWITCH;
use const T_TRY;
use const T_WHILE;

class BlockControlStructureSpacingSniff extends AbstractControlStructureSpacing
{

	/** @var int */
	public $linesCountBeforeControlStructure = 1;

	/** @var int */
	public $linesCountBeforeFirstControlStructure = 0;

	/** @var int */
	public $linesCountAfterControlStructure = 1;

	/** @var int */
	public $linesCountAfterLastControlStructure = 0;

	/** @var string[] */
	public $tokensToCheck = [];

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $controlStructurePointer
	 */
	public function process(File $phpcsFile, $controlStructurePointer): void
	{
		if ($this->isWhilePartOfDo($phpcsFile, $controlStructurePointer)) {
			return;
		}

		parent::process($phpcsFile, $controlStructurePointer);
	}

	/**
	 * @return int[]
	 */
	protected function getSupportedTokens(): array
	{
		return [
			T_IF,
			T_DO,
			T_WHILE,
			T_FOR,
			T_FOREACH,
			T_SWITCH,
			T_TRY,
			T_CASE,
			T_DEFAULT,
		];
	}

	/**
	 * @return string[]
	 */
	protected function getTokensToCheck(): array
	{
		return $this->tokensToCheck;
	}

	protected function getLinesCountBefore(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountBeforeControlStructure);
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
	 * @param File $phpcsFile
	 * @param int $controlStructurePointer
	 * @return int
	 */
	protected function getLinesCountBeforeFirst(File $phpcsFile, int $controlStructurePointer): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountBeforeFirstControlStructure);
	}

	protected function getLinesCountAfter(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountAfterControlStructure);
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
	 * @param File $phpcsFile
	 * @param int $controlStructurePointer
	 * @param int $controlStructureEndPointer
	 * @return int
	 */
	protected function getLinesCountAfterLast(File $phpcsFile, int $controlStructurePointer, int $controlStructureEndPointer): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastControlStructure);
	}

	private function isWhilePartOfDo(File $phpcsFile, int $controlStructurePointer): bool
	{
		$tokens = $phpcsFile->getTokens();
		$pointerBefore = TokenHelper::findPreviousEffective($phpcsFile, $controlStructurePointer - 1);

		return
			$tokens[$controlStructurePointer]['code'] === T_WHILE
			&& $tokens[$pointerBefore]['code'] === T_CLOSE_CURLY_BRACKET
			&& array_key_exists('scope_condition', $tokens[$pointerBefore])
			&& $tokens[$tokens[$pointerBefore]['scope_condition']]['code'] === T_DO;
	}

}
