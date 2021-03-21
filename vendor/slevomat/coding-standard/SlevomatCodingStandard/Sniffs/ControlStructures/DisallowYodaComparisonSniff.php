<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\YodaHelper;
use function array_keys;
use function count;
use const T_EQUAL;
use const T_IS_EQUAL;
use const T_IS_IDENTICAL;
use const T_IS_NOT_EQUAL;
use const T_IS_NOT_IDENTICAL;

/**
 * Bigger value must be on the left side:
 *
 * ($variable, Foo::$class, Foo::bar(), foo())
 *  > (Foo::BAR, BAR)
 *  > (true, false, null, 1, 1.0, arrays, 'foo')
 */
class DisallowYodaComparisonSniff implements Sniff
{

	public const CODE_DISALLOWED_YODA_COMPARISON = 'DisallowedYodaComparison';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_IS_IDENTICAL,
			T_IS_NOT_IDENTICAL,
			T_IS_EQUAL,
			T_IS_NOT_EQUAL,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $comparisonTokenPointer
	 */
	public function process(File $phpcsFile, $comparisonTokenPointer): void
	{
		$tokens = $phpcsFile->getTokens();
		$leftSideTokens = YodaHelper::getLeftSideTokens($tokens, $comparisonTokenPointer);
		$rightSideTokens = YodaHelper::getRightSideTokens($tokens, $comparisonTokenPointer);
		$leftDynamism = YodaHelper::getDynamismForTokens($tokens, $leftSideTokens);
		$rightDynamism = YodaHelper::getDynamismForTokens($tokens, $rightSideTokens);

		if ($leftDynamism === null || $rightDynamism === null) {
			return;
		}

		if ($leftDynamism >= $rightDynamism) {
			return;
		}

		if ($leftDynamism >= 900 && $rightDynamism >= 900) {
			return;
		}

		$errorParameters = [
			'Yoda comparisons are disallowed.',
			$comparisonTokenPointer,
			self::CODE_DISALLOWED_YODA_COMPARISON,
		];

		$lastRightSideTokenPointer = array_keys($rightSideTokens)[count($rightSideTokens) - 1];

		$nextPointer = TokenHelper::findNextEffective($phpcsFile, $lastRightSideTokenPointer + 1);
		if ($tokens[$nextPointer]['code'] === T_EQUAL) {
			$phpcsFile->addError(...$errorParameters);
			return;
		}

		$fix = $phpcsFile->addFixableError(...$errorParameters);
		if (!$fix) {
			return;
		}

		YodaHelper::fix($phpcsFile, $leftSideTokens, $rightSideTokens);
	}

}
