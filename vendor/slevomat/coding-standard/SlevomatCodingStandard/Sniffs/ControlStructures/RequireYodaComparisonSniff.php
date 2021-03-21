<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\YodaHelper;
use function count;
use const T_IS_EQUAL;
use const T_IS_IDENTICAL;
use const T_IS_NOT_EQUAL;
use const T_IS_NOT_IDENTICAL;

/**
 * Bigger value must be on the right side:
 *
 * ($variable, Foo::$class, Foo::bar(), foo())
 *  > (Foo::BAR, BAR)
 *  > (true, false, null, 1, 1.0, arrays, 'foo')
 */
class RequireYodaComparisonSniff implements Sniff
{

	public const CODE_REQUIRED_YODA_COMPARISON = 'RequiredYodaComparison';

	/** @var bool */
	public $alwaysVariableOnRight = false;

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

		if ($leftDynamism <= $rightDynamism) {
			return;
		}

		if (!$this->alwaysVariableOnRight && $leftDynamism >= 900 && $rightDynamism >= 900) {
			return;
		}

		$fix = $phpcsFile->addFixableError('Yoda comparison is required.', $comparisonTokenPointer, self::CODE_REQUIRED_YODA_COMPARISON);
		if (!$fix || count($leftSideTokens) === 0 || count($rightSideTokens) === 0) {
			return;
		}

		YodaHelper::fix($phpcsFile, $leftSideTokens, $rightSideTokens);
	}

}
