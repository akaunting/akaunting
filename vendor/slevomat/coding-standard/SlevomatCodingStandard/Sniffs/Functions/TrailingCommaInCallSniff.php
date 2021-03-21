<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function array_merge;
use function in_array;
use const T_CLOSE_PARENTHESIS;
use const T_COMMA;
use const T_ISSET;
use const T_OPEN_PARENTHESIS;
use const T_PARENT;
use const T_SELF;
use const T_STATIC;
use const T_UNSET;
use const T_VARIABLE;

class TrailingCommaInCallSniff implements Sniff
{

	public const CODE_MISSING_TRAILING_COMMA = 'MissingTrailingComma';

	/** @var bool|null */
	public $enable = null;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_OPEN_PARENTHESIS,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $parenthesisOpenerPointer
	 */
	public function process(File $phpcsFile, $parenthesisOpenerPointer): void
	{
		$this->enable = SniffSettingsHelper::isEnabledByPhpVersion($this->enable, 70300);

		if (!$this->enable) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		if (array_key_exists('parenthesis_owner', $tokens[$parenthesisOpenerPointer])) {
			return;
		}

		$pointerBeforeParenthesisOpener = TokenHelper::findPreviousEffective($phpcsFile, $parenthesisOpenerPointer - 1);
		if (!in_array(
			$tokens[$pointerBeforeParenthesisOpener]['code'],
			array_merge(
				TokenHelper::getOnlyNameTokenCodes(),
				[T_VARIABLE, T_ISSET, T_UNSET, T_CLOSE_PARENTHESIS, T_SELF, T_STATIC, T_PARENT]
			),
			true
		)) {
			return;
		}

		$parenthesisCloserPointer = $tokens[$parenthesisOpenerPointer]['parenthesis_closer'];

		if ($tokens[$parenthesisOpenerPointer]['line'] === $tokens[$parenthesisCloserPointer]['line']) {
			return;
		}

		$pointerBeforeParenthesisCloser = TokenHelper::findPreviousEffective($phpcsFile, $parenthesisCloserPointer - 1);
		if ($pointerBeforeParenthesisCloser === $parenthesisOpenerPointer) {
			return;
		}

		if ($pointerBeforeParenthesisCloser + 1 === $parenthesisCloserPointer) {
			return;
		}

		if ($tokens[$pointerBeforeParenthesisCloser]['code'] === T_COMMA) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Multi-line function calls must have a trailing comma after the last parameter.',
			$pointerBeforeParenthesisCloser,
			self::CODE_MISSING_TRAILING_COMMA
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->addContent($pointerBeforeParenthesisCloser, ',');
		$phpcsFile->fixer->endChangeset();
	}

}
