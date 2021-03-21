<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function count;
use function in_array;
use function ltrim;
use function sprintf;
use function strtolower;
use function trim;
use const T_COMMA;
use const T_DOUBLE_COLON;
use const T_FUNCTION;
use const T_OBJECT_OPERATOR;
use const T_OPEN_PARENTHESIS;
use const T_OPEN_SHORT_ARRAY;

class StrictCallSniff implements Sniff
{

	public const CODE_STRICT_PARAMETER_MISSING = 'StrictParameterMissing';
	public const CODE_NON_STRICT_COMPARISON = 'NonStrictComparison';

	private const FUNCTIONS = [
		'in_array' => 3,
		'array_search' => 3,
		'base64_decode' => 2,
		'array_keys' => 3,
	];

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return TokenHelper::getOnlyNameTokenCodes();
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $stringPointer
	 */
	public function process(File $phpcsFile, $stringPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$parenthesisOpenerPointer = TokenHelper::findNextEffective($phpcsFile, $stringPointer + 1);
		if ($tokens[$parenthesisOpenerPointer]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}

		$parenthesisCloserPointer = $tokens[$parenthesisOpenerPointer]['parenthesis_closer'];

		$functionName = ltrim(strtolower($tokens[$stringPointer]['content']), '\\');

		if (!array_key_exists($functionName, self::FUNCTIONS)) {
			return;
		}

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $stringPointer - 1);
		if (in_array($tokens[$previousPointer]['code'], [T_OBJECT_OPERATOR, T_DOUBLE_COLON, T_FUNCTION], true)) {
			return;
		}

		$commaPointers = [];
		for ($i = $parenthesisOpenerPointer + 1; $i < $parenthesisCloserPointer; $i++) {
			if ($tokens[$i]['code'] === T_OPEN_PARENTHESIS) {
				$i = $tokens[$i]['parenthesis_closer'];
				continue;
			}

			if ($tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
				$i = $tokens[$i]['bracket_closer'];
				continue;
			}

			if ($tokens[$i]['code'] === T_COMMA) {
				$commaPointers[] = $i;
			}
		}

		$commaPointersCount = count($commaPointers);

		$parametersCount = $commaPointersCount + 1;
		$lastCommaPointer = $commaPointersCount > 0 ? $commaPointers[$commaPointersCount - 1] : null;
		$hasTrailingComma = false;

		if (
			$lastCommaPointer !== null
			&& TokenHelper::findNextEffective($phpcsFile, $lastCommaPointer + 1, $parenthesisCloserPointer) === null
		) {
			$hasTrailingComma = true;
			$parametersCount--;
		}

		if ($parametersCount === self::FUNCTIONS[$functionName]) {

			$strictParameterValue = TokenHelper::getContent(
				$phpcsFile,
				$commaPointers[self::FUNCTIONS[$functionName] - 2] + 1,
				($hasTrailingComma ? $lastCommaPointer : $parenthesisCloserPointer) - 1
			);

			if (strtolower(trim($strictParameterValue)) === 'true') {
				return;
			}

			$phpcsFile->addError(
				sprintf('Strict parameter should be set to true in %s() call.', $functionName),
				$stringPointer,
				self::CODE_NON_STRICT_COMPARISON
			);

		} elseif ($parametersCount === self::FUNCTIONS[$functionName] - 1) {
			$phpcsFile->addError(
				sprintf('Strict parameter missing in %s() call.', $functionName),
				$stringPointer,
				self::CODE_STRICT_PARAMETER_MISSING
			);
		}
	}

}
