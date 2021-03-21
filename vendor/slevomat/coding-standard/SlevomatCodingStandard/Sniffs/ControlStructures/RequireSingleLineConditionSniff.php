<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use function strlen;

class RequireSingleLineConditionSniff extends AbstractLineCondition
{

	public const CODE_REQUIRED_SINGLE_LINE_CONDITION = 'RequiredSingleLineCondition';

	/** @var int */
	public $maxLineLength = 120;

	/** @var bool */
	public $alwaysForSimpleConditions = true;

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $controlStructurePointer
	 */
	public function process(File $phpcsFile, $controlStructurePointer): void
	{
		if ($this->shouldBeSkipped($phpcsFile, $controlStructurePointer)) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$parenthesisOpenerPointer = $tokens[$controlStructurePointer]['parenthesis_opener'];
		$parenthesisCloserPointer = $tokens[$controlStructurePointer]['parenthesis_closer'];

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

		$lineStart = $this->getLineStart($phpcsFile, $parenthesisOpenerPointer);
		$condition = $this->getCondition($phpcsFile, $parenthesisOpenerPointer, $parenthesisCloserPointer);
		$lineEnd = $this->getLineEnd($phpcsFile, $parenthesisCloserPointer);

		$lineLength = strlen($lineStart . $condition . $lineEnd);
		$isSimpleCondition = TokenHelper::findNext(
			$phpcsFile,
			Tokens::$booleanOperators,
			$parenthesisOpenerPointer + 1,
			$parenthesisCloserPointer
		) === null;

		if (!$this->shouldReportError($lineLength, $isSimpleCondition)) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Condition of "%s" should be placed on a single line.',
				$this->getControlStructureName($phpcsFile, $controlStructurePointer)
			),
			$controlStructurePointer,
			self::CODE_REQUIRED_SINGLE_LINE_CONDITION
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->addContent($parenthesisOpenerPointer, $condition);

		for ($i = $parenthesisOpenerPointer + 1; $i < $parenthesisCloserPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	private function shouldReportError(int $lineLength, bool $isSimpleCondition): bool
	{
		$maxLineLength = SniffSettingsHelper::normalizeInteger($this->maxLineLength);

		if ($maxLineLength === 0) {
			return true;
		}

		if ($lineLength <= $maxLineLength) {
			return true;
		}

		return $isSimpleCondition && $this->alwaysForSimpleConditions;
	}

}
