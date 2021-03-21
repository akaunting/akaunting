<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Variables;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use function in_array;
use const T_VARIABLE;

class DisallowSuperGlobalVariableSniff implements Sniff
{

	public const CODE_DISALLOWED_SUPER_GLOBAL_VARIABLE = 'DisallowedSuperGlobalVariable';

	private const SUPER_GLOBALS = [
		'$GLOBALS',
		'$_SERVER',
		'$_GET',
		'$_POST',
		'$_FILES',
		'$_COOKIE',
		'$_SESSION',
		'$_REQUEST',
		'$_ENV',
	];

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_VARIABLE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $variablePointer
	 */
	public function process(File $phpcsFile, $variablePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if (!in_array($tokens[$variablePointer]['content'], self::SUPER_GLOBALS, true)) {
			return;
		}

		$phpcsFile->addError('Use of super global variable is disallowed.', $variablePointer, self::CODE_DISALLOWED_SUPER_GLOBAL_VARIABLE);
	}

}
