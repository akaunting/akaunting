<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use function sprintf;
use function strtolower;
use function substr;
use const T_INTERFACE;

class SuperfluousInterfaceNamingSniff implements Sniff
{

	public const CODE_SUPERFLUOUS_PREFIX = 'SuperfluousPrefix';
	public const CODE_SUPERFLUOUS_SUFFIX = 'SuperfluousSuffix';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_INTERFACE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $interfacePointer
	 */
	public function process(File $phpcsFile, $interfacePointer): void
	{
		$interfaceName = ClassHelper::getName($phpcsFile, $interfacePointer);

		$this->checkPrefix($phpcsFile, $interfacePointer, $interfaceName);
		$this->checkSuffix($phpcsFile, $interfacePointer, $interfaceName);
	}

	private function checkPrefix(File $phpcsFile, int $interfacePointer, string $interfaceName): void
	{
		$prefix = substr($interfaceName, 0, 9);

		if (strtolower($prefix) !== 'interface') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous prefix "%s".', $prefix), $interfacePointer, self::CODE_SUPERFLUOUS_PREFIX);
	}

	private function checkSuffix(File $phpcsFile, int $interfacePointer, string $interfaceName): void
	{
		$suffix = substr($interfaceName, -9);

		if (strtolower($suffix) !== 'interface') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous suffix "%s".', $suffix), $interfacePointer, self::CODE_SUPERFLUOUS_SUFFIX);
	}

}
