<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Operators;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function sprintf;
use const T_BITWISE_AND;
use const T_BITWISE_OR;
use const T_BITWISE_XOR;
use const T_DIVIDE;
use const T_EQUAL;
use const T_MINUS;
use const T_MODULUS;
use const T_MULTIPLY;
use const T_PLUS;
use const T_POW;
use const T_SEMICOLON;
use const T_SL;
use const T_SR;
use const T_STRING_CONCAT;

class RequireCombinedAssignmentOperatorSniff implements Sniff
{

	public const CODE_REQUIRED_COMBINED_ASSIGMENT_OPERATOR = 'RequiredCombinedAssigmentOperator';

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
		/** @var int $variableStartPointer */
		$variableStartPointer = TokenHelper::findNextEffective($phpcsFile, $equalPointer + 1);
		$variableEndPointer = IdentificatorHelper::findEndPointer($phpcsFile, $variableStartPointer);

		if ($variableEndPointer === null) {
			return;
		}

		$operatorPointer = TokenHelper::findNextEffective($phpcsFile, $variableEndPointer + 1);
		$tokens = $phpcsFile->getTokens();

		$operators = [
			T_BITWISE_AND => '&=',
			T_BITWISE_OR => '|=',
			T_STRING_CONCAT => '.=',
			T_DIVIDE => '/=',
			T_MINUS => '-=',
			T_POW => '**=',
			T_MODULUS => '%=',
			T_MULTIPLY => '*=',
			T_PLUS => '+=',
			T_SL => '<<=',
			T_SR => '>>=',
			T_BITWISE_XOR => '^=',
		];

		if (!array_key_exists($tokens[$operatorPointer]['code'], $operators)) {
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
		if (TokenHelper::findNext($phpcsFile, Tokens::$operators, $operatorPointer + 1, $semicolonPointer) !== null) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Use "%s" operator instead of "=" and "%s".',
				$operators[$tokens[$operatorPointer]['code']],
				$tokens[$operatorPointer]['content']
			),
			$equalPointer,
			self::CODE_REQUIRED_COMBINED_ASSIGMENT_OPERATOR
		);

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($equalPointer, $operators[$tokens[$operatorPointer]['code']]);
		for ($i = $equalPointer + 1; $i <= $operatorPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
