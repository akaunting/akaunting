<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use const T_OPEN_USE_GROUP;

class DisallowGroupUseSniff implements Sniff
{

	public const CODE_DISALLOWED_GROUP_USE = 'DisallowedGroupUse';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_OPEN_USE_GROUP,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $usePointer
	 */
	public function process(File $phpcsFile, $usePointer): void
	{
		$phpcsFile->addError(
			'Group use declaration is disallowed, use single use for every import.',
			$usePointer,
			self::CODE_DISALLOWED_GROUP_USE
		);
	}

}
