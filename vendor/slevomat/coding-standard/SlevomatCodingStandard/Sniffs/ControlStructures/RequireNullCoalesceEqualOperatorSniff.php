<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_COALESCE;
use const T_EQUAL;
use const T_SEMICOLON;

class RequireNullCoalesceEqualOperatorSniff implements Sniff
{

	public const CODE_REQUIRED_NULL_COALESCE_EQUAL_OPERATOR = 'RequiredNullCoalesceEqualOperator';

	/** @var bool|null */
	public $enable = null;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_EQUAL,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $equalPointer
	 */
	public function process(File $phpcsFile, $equalPointer): void
	{
		$this->enable = SniffSettingsHelper::isEnabledByPhpVersion($this->enable, 70400);

		if (!$this->enable) {
			return;
		}

		/** @var int $variableStartPointer */
		$variableStartPointer = TokenHelper::findNextEffective($phpcsFile, $equalPointer + 1);
		$variableEndPointer = IdentificatorHelper::findEndPointer($phpcsFile, $variableStartPointer);

		if ($variableEndPointer === null) {
			return;
		}

		$nullCoalescePointer = TokenHelper::findNextEffective($phpcsFile, $variableEndPointer + 1);
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$nullCoalescePointer]['code'] !== T_COALESCE) {
			return;
		}

		$variableContent = IdentificatorHelper::getContent($phpcsFile, $variableStartPointer, $variableEndPointer);

		/** @var int $beforeEqualEndPointer */
		$beforeEqualEndPointer = TokenHelper::findPreviousEffective($phpcsFile, $equalPointer - 1);
		$beforeEqualStartPointer = IdentificatorHelper::findStartPointer($phpcsFile, $beforeEqualEndPointer);

		if ($beforeEqualStartPointer === null) {
			return;
		}

		$beforeEqualVariableContent = IdentificatorHelper::getContent($phpcsFile, $beforeEqualStartPointer, $beforeEqualEndPointer);

		if ($beforeEqualVariableContent !== $variableContent) {
			return;
		}

		$semicolonPointer = TokenHelper::findNext($phpcsFile, T_SEMICOLON, $equalPointer + 1);
		if (TokenHelper::findNext($phpcsFile, Tokens::$operators, $nullCoalescePointer + 1, $semicolonPointer) !== null) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Use "??=" operator instead of "=" and "??".',
			$equalPointer,
			self::CODE_REQUIRED_NULL_COALESCE_EQUAL_OPERATOR
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($equalPointer, '??=');
		for ($i = $equalPointer + 1; $i <= $nullCoalescePointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
