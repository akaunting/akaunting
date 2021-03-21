<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function count;
use function sprintf;
use function strtolower;
use const T_COMMA;

class UselessParameterDefaultValueSniff implements Sniff
{

	public const CODE_USELESS_PARAMETER_DEFAULT_VALUE = 'UselessParameterDefaultValue';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return TokenHelper::$functionTokenCodes;
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 */
	public function process(File $phpcsFile, $functionPointer): void
	{
		$parameters = $phpcsFile->getMethodParameters($functionPointer);
		$parametersCount = count($parameters);

		if ($parametersCount === 0) {
			return;
		}

		for ($i = 0; $i < $parametersCount; $i++) {
			$parameter = $parameters[$i];

			if (!array_key_exists('default', $parameter)) {
				continue;
			}

			$defaultValue = strtolower($parameter['default']);
			if ($defaultValue === 'null' && !$parameter['nullable_type']) {
				continue;
			}

			for ($j = $i + 1; $j < $parametersCount; $j++) {
				$nextParameter = $parameters[$j];

				if (array_key_exists('default', $nextParameter)) {
					continue;
				}

				if ($nextParameter['variable_length']) {
					break;
				}

				$fix = $phpcsFile->addFixableError(
					sprintf('Useless default value of parameter %s.', $parameter['name']),
					$parameter['token'],
					self::CODE_USELESS_PARAMETER_DEFAULT_VALUE
				);

				if (!$fix) {
					continue;
				}

				$commaPointer = TokenHelper::findPrevious($phpcsFile, T_COMMA, $parameters[$i + 1]['token'] - 1);
				/** @var int $parameterPointer */
				$parameterPointer = $parameter['token'];

				$phpcsFile->fixer->beginChangeset();
				for ($k = $parameterPointer + 1; $k < $commaPointer; $k++) {
					$phpcsFile->fixer->replaceToken($k, '');
				}
				$phpcsFile->fixer->endChangeset();

				break;
			}
		}
	}

}
