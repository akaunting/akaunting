<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use function sprintf;
use function strtolower;
use function substr;
use const T_TRAIT;

class SuperfluousTraitNamingSniff implements Sniff
{

	public const CODE_SUPERFLUOUS_SUFFIX = 'SuperfluousSuffix';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_TRAIT,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $traitPointer
	 */
	public function process(File $phpcsFile, $traitPointer): void
	{
		$traitName = ClassHelper::getName($phpcsFile, $traitPointer);

		$this->checkSuffix($phpcsFile, $traitPointer, $traitName);
	}

	private function checkSuffix(File $phpcsFile, int $traitPointer, string $traitName): void
	{
		$suffix = substr($traitName, -5);

		if (strtolower($suffix) !== 'trait') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous suffix "%s".', $suffix), $traitPointer, self::CODE_SUPERFLUOUS_SUFFIX);
	}

}
