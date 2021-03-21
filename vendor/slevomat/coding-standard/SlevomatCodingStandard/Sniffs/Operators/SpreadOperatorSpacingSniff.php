<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Operators;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use function str_repeat;
use function strlen;
use const T_ELLIPSIS;
use const T_WHITESPACE;

class SpreadOperatorSpacingSniff implements Sniff
{

	public const CODE_INCORRECT_SPACES_AFTER_OPERATOR = 'IncorrectSpacesAfterOperator';

	/** @var int */
	public $spacesCountAfterOperator = 0;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_ELLIPSIS,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $spreadOperatorPointer
	 */
	public function process(File $phpcsFile, $spreadOperatorPointer): void
	{
		$pointerAfterWhitespace = TokenHelper::findNextExcluding($phpcsFile, T_WHITESPACE, $spreadOperatorPointer + 1);

		$whitespace = TokenHelper::getContent($phpcsFile, $spreadOperatorPointer + 1, $pointerAfterWhitespace - 1);

		$requiredSpacesCountAfterOperator = SniffSettingsHelper::normalizeInteger($this->spacesCountAfterOperator);
		$actualSpacesCountAfterOperator = strlen($whitespace);

		if ($requiredSpacesCountAfterOperator === $actualSpacesCountAfterOperator) {
			return;
		}

		$errorMessage = $requiredSpacesCountAfterOperator === 0
			? 'There must be no whitespace after spread operator.'
			: sprintf(
				'There must be exactly %d whitespace%s after spread operator.',
				$requiredSpacesCountAfterOperator,
				$requiredSpacesCountAfterOperator !== 1 ? 's' : ''
			);

		$fix = $phpcsFile->addFixableError($errorMessage, $spreadOperatorPointer, self::CODE_INCORRECT_SPACES_AFTER_OPERATOR);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->addContent($spreadOperatorPointer, str_repeat(' ', $requiredSpacesCountAfterOperator));
		for ($i = $spreadOperatorPointer + 1; $i < $pointerAfterWhitespace; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

}
